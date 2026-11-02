<?php

namespace BrandedHosting\Platform\Jobs;

use App\Enums\SuspendAction;
use App\Services\Servers\SuspensionService;
use BrandedHosting\Platform\Enums\BillingPeriod;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Notifications\BillingNotice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Applies an approved renewal: extends the paid-until date on the original order and lifts a suspension.
 *
 * Both steps are safe to repeat, so a retry after a failed unsuspend never extends the date twice.
 */
class ApplyRenewalJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    /** @var int[] */
    public array $backoff = [30, 120, 600, 1800];

    public int $uniqueFor = 900;

    public function __construct(public int $orderId) {}

    public function uniqueId(): string
    {
        return 'hosting-renewal-order-' . $this->orderId;
    }

    public function handle(SuspensionService $suspensionService): void
    {
        $extended = DB::transaction(function () {
            /** @var HostingOrder|null $renewal */
            $renewal = HostingOrder::query()->whereKey($this->orderId)->lockForUpdate()->first();
            if (!$renewal || !$renewal->isRenewal() || !$renewal->renews_order_id) {
                Log::error("[Renewal] Order #{$this->orderId} is not a renewal.");

                return false;
            }
            if ($renewal->status === OrderStatus::Completed) {
                return false; // already applied; only the unsuspend step may still be pending
            }
            if ($renewal->status !== OrderStatus::Approved) {
                Log::warning("[Renewal] {$renewal->order_number} is {$renewal->status->value}; not applying.");

                return false;
            }

            /** @var HostingOrder $parent */
            $parent = HostingOrder::query()->whereKey($renewal->renews_order_id)->lockForUpdate()->firstOrFail();

            $days = BillingPeriod::tryFrom($renewal->plan_snapshot['billing_period'] ?? '')?->getDurationInDays() ?? 30;
            // Paying early keeps the days you had left; paying late starts from today.
            $base = $parent->paid_until && $parent->paid_until->isFuture() ? $parent->paid_until : now();

            $parent->update(['paid_until' => $base->copy()->addDays($days), 'billing_stage' => 0]);
            $renewal->update([
                'server_id' => $parent->server_id,
                'status' => OrderStatus::Completed,
                'provisioned_at' => now(),
            ]);

            return true;
        });

        $renewal = HostingOrder::with(['renewsOrder.server', 'user'])->find($this->orderId);
        $parent = $renewal?->renewsOrder;
        if (!$parent) {
            return;
        }

        // Lift the suspension if there is one. A failure here is retried; the date is already extended.
        if ($parent->suspended_at) {
            $server = $parent->server;
            if ($server && $server->isSuspended()) {
                $suspensionService->handle($server, SuspendAction::Unsuspend);
            }
            $parent->update(['suspended_at' => null]);
        }

        if ($extended) {
            $renewal->user->notify(new BillingNotice(BillingNotice::RENEWED, $parent));
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error("[Renewal] Failed for order #{$this->orderId}: " . $exception->getMessage());
    }
}
