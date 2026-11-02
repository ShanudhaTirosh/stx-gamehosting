<?php

namespace BrandedHosting\Platform\Console\Commands;

use App\Enums\SuspendAction;
use App\Services\Servers\SuspensionService;
use BrandedHosting\Platform\Enums\BillingPeriod;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Notifications\BillingNotice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Hourly billing run: invoice upcoming renewals, remind, suspend unpaid servers.
 *
 * Servers are only ever suspended here, never deleted. Removing an unpaid server is a staff decision.
 */
class RunBillingCommand extends Command
{
    protected $signature = 'hosting:run-billing {--dry-run : Show what would happen without changing anything}';

    protected $description = 'Create renewal invoices, send reminders and suspend servers that are past due';

    public function handle(SuspensionService $suspensionService): int
    {
        $invoiceDays = (int) config('hosting-platform.billing.invoice_days_before', 7);
        $reminderDays = (int) config('hosting-platform.billing.reminder_days_before', 3);
        $finalDays = (int) config('hosting-platform.billing.final_reminder_days_before', 1);
        $graceDays = (int) config('hosting-platform.billing.suspend_grace_days', 1);
        $dry = (bool) $this->option('dry-run');

        $counts = ['invoiced' => 0, 'reminded' => 0, 'suspended' => 0, 'errors' => 0];

        HostingOrder::query()
            ->where('order_type', 'new')
            ->where('status', OrderStatus::Completed->value)
            ->whereNotNull('server_id')
            ->whereNotNull('paid_until')
            ->with(['user', 'server'])
            ->orderBy('id')
            ->chunkById(100, function ($orders) use (&$counts, $invoiceDays, $reminderDays, $finalDays, $graceDays, $dry, $suspensionService) {
                foreach ($orders as $order) {
                    try {
                        $this->process($order, $counts, $invoiceDays, $reminderDays, $finalDays, $graceDays, $dry, $suspensionService);
                    } catch (Throwable $e) {
                        $counts['errors']++;
                        report($e);
                        $this->error("{$order->order_number}: {$e->getMessage()}");
                    }
                }
            });

        $this->info(($dry ? '[dry run] ' : '') . "Invoiced {$counts['invoiced']}, reminded {$counts['reminded']}, suspended {$counts['suspended']}, errors {$counts['errors']}.");

        return $counts['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @param  array<string, int>  $counts
     */
    protected function process(HostingOrder $order, array &$counts, int $invoiceDays, int $reminderDays, int $finalDays, int $graceDays, bool $dry, SuspensionService $suspensionService): void
    {
        $now = now();
        $due = $order->paid_until;
        $open = $order->openRenewal();
        $inReview = $open?->status === OrderStatus::UnderReview;

        // 1. Invoice ahead of time (or again, if the last one lapsed on an already-suspended server).
        if (!$open && $due->lte($now->copy()->addDays($invoiceDays))) {
            if (!$dry) {
                $open = $this->createRenewal($order);
                $order->update(['billing_stage' => max($order->billing_stage, 1)]);
                $order->user->notify(new BillingNotice(BillingNotice::INVOICE, $order, $open));
            }
            $counts['invoiced']++;

            return;
        }

        // 2. Reminders, only while nothing has been paid or submitted and the server is still running.
        if ($open && !$inReview && $due->isFuture()) {
            $stage = $order->billing_stage;
            $type = null;
            $newStage = 0;
            if ($due->lte($now->copy()->addDays($finalDays)) && $stage < 3) {
                $type = BillingNotice::FINAL_REMINDER;
                $newStage = 3;
            } elseif ($due->lte($now->copy()->addDays($reminderDays)) && $stage < 2) {
                $type = BillingNotice::REMINDER;
                $newStage = 2;
            }
            if ($type) {
                if (!$dry) {
                    $order->update(['billing_stage' => $newStage]);
                    $order->user->notify(new BillingNotice($type, $order, $open));
                }
                $counts['reminded']++;
            }
        }

        // 3. Suspend after the grace period, unless a payment is waiting to be checked by staff.
        //    The customer must also have had the invoice for at least 24 hours, so a billing run that
        //    was switched off for a while never invoices and suspends in the same breath.
        $noticed = $open && $open->created_at->lte($now->copy()->subHours(24));
        if ($noticed && !$inReview && !$order->suspended_at && $due->copy()->addDays($graceDays)->lte($now)) {
            if (!$dry) {
                $server = $order->server;
                if ($server && !$server->isSuspended()) {
                    $suspensionService->handle($server, SuspendAction::Suspend);
                }
                $order->update(['suspended_at' => $now, 'billing_stage' => 4]);
                $order->user->notify(new BillingNotice(BillingNotice::SUSPENDED, $order, $open));
            }
            $counts['suspended']++;
        }
    }

    protected function createRenewal(HostingOrder $order): HostingOrder
    {
        return DB::transaction(function () use ($order) {
            $snapshot = $order->plan_snapshot ?? [];

            // Renew at today's price when the plan still exists, otherwise at the price originally paid.
            $plan = $order->plan;
            $price = $plan && $plan->is_available ? (float) $plan->price_lkr : (float) ($snapshot['price_lkr'] ?? $order->subtotal_lkr);
            $snapshot['price_lkr'] = $price;
            $snapshot['setup_fee_lkr'] = 0.0;

            return HostingOrder::create([
                'order_type' => 'renewal',
                'renews_order_id' => $order->id,
                'user_id' => $order->user_id,
                'plan_id' => $order->plan_id,
                'payment_method_id' => $order->payment_method_id,
                'server_id' => $order->server_id,
                'status' => OrderStatus::PendingPayment,
                'subtotal_lkr' => $price,
                'discount_lkr' => 0.00,
                'total_amount_lkr' => $price,
                'plan_snapshot' => $snapshot,
                'server_configuration' => $order->server_configuration,
                // Stays payable well after suspension, so a late customer can still renew.
                'expires_at' => $order->paid_until->copy()->addDays((int) config('hosting-platform.billing.renewal_valid_days', 30)),
            ]);
        });
    }
}
