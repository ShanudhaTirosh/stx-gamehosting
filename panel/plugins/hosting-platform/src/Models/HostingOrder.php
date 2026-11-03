<?php

namespace BrandedHosting\Platform\Models;

use App\Models\Server;
use App\Models\User;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Enums\VerificationStatus;
use BrandedHosting\Platform\Jobs\ApplyRenewalJob;
use BrandedHosting\Platform\Jobs\ProvisionPaidServerJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HostingOrder extends Model
{
    protected $table = 'hosting_orders';

    protected $fillable = [
        'uuid',
        'order_number',
        'order_type',
        'renews_order_id',
        'user_id',
        'plan_id',
        'payment_method_id',
        'server_id',
        'status',
        'subtotal_lkr',
        'discount_lkr',
        'total_amount_lkr',
        'plan_snapshot',
        'server_configuration',
        'expires_at',
        'approved_at',
        'provisioned_at',
        'paid_until',
        'suspended_at',
        'billing_stage',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal_lkr' => 'decimal:2',
        'discount_lkr' => 'decimal:2',
        'total_amount_lkr' => 'decimal:2',
        'plan_snapshot' => 'array',
        'server_configuration' => 'array',
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
        'provisioned_at' => 'datetime',
        'paid_until' => 'datetime',
        'suspended_at' => 'datetime',
        'billing_stage' => 'integer',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (HostingOrder $order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(static::class, 'renews_order_id');
    }

    public function renewsOrder(): BelongsTo
    {
        return $this->belongsTo(static::class, 'renews_order_id');
    }

    public function isRenewal(): bool
    {
        return $this->order_type === 'renewal';
    }

    /**
     * The unpaid renewal invoice for this server, if there is one.
     */
    public function openRenewal(): ?self
    {
        return $this->renewals()
            ->whereIn('status', [OrderStatus::PendingPayment->value, OrderStatus::UnderReview->value])
            ->latest('id')
            ->first();
    }

    /**
     * Queue the right fulfilment job: a new server for a new order, or a date extension for a renewal.
     */
    public function queueFulfilment(): void
    {
        $this->isRenewal()
            ? ApplyRenewalJob::dispatch($this->getKey())
            : ProvisionPaidServerJob::dispatch($this->getKey());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(HostingPlan::class, 'plan_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(HostingPaymentMethod::class, 'payment_method_id');
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class, 'server_id');
    }

    public function paymentSubmissions(): HasMany
    {
        return $this->hasMany(HostingPaymentSubmission::class, 'order_id')->latest();
    }

    public function latestSubmission(): HasOne
    {
        return $this->hasOne(HostingPaymentSubmission::class, 'order_id')->latestOfMany();
    }

    public function provisioningRecord(): HasOne
    {
        return $this->hasOne(HostingProvisioningRecord::class, 'order_id')->latestOfMany();
    }

    /**
     * Approve payment and queue provisioning exactly once.
     *
     * The order row is locked, so two staff members clicking at the same time (or a double click)
     * cannot both approve it. Returns false when the order is no longer awaiting approval.
     */
    public function approveAndQueueProvisioning(?int $staffUserId, string $note): bool
    {
        $approved = DB::transaction(function () use ($staffUserId, $note) {
            /** @var HostingOrder|null $order */
            $order = static::query()->whereKey($this->getKey())->lockForUpdate()->first();

            $approvable = [OrderStatus::PendingPayment, OrderStatus::UnderReview, OrderStatus::Approved];
            if (!$order || (!$order->isRenewal() && $order->server_id !== null) || !in_array($order->status, $approvable, true)) {
                return false;
            }

            $order->update([
                'status' => OrderStatus::Approved,
                'approved_at' => $order->approved_at ?? now(),
            ]);

            if ($submission = $order->latestSubmission) {
                $submission->update([
                    'verification_status' => VerificationStatus::Approved,
                    'verified_by_user_id' => $staffUserId,
                    'verified_at' => now(),
                    'staff_decision_notes' => $note,
                ]);
            }

            return true;
        });

        if ($approved) {
            // Outside the transaction so the worker always sees the committed row.
            $this->refresh()->queueFulfilment();
        }

        return $approved;
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rs. ' . number_format((float) $this->total_amount_lkr, 2) . ' LKR';
    }
}
