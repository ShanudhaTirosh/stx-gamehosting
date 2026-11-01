<?php

namespace BrandedHosting\Platform\Models;

use App\Models\Server;
use App\Models\User;
use BrandedHosting\Platform\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class HostingOrder extends Model
{
    protected $table = 'hosting_orders';

    protected $fillable = [
        'uuid',
        'order_number',
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

    public function getFormattedTotalAttribute(): string
    {
        return 'Rs. ' . number_format((float) $this->total_amount_lkr, 2) . ' LKR';
    }
}
