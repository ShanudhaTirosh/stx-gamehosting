<?php

namespace BrandedHosting\Platform\Models;

use BrandedHosting\Platform\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostingPaymentMethod extends Model
{
    protected $table = 'hosting_payment_methods';

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'recipient_name',
        'account_or_mobile_number',
        'bank_name',
        'branch_name',
        'payment_instructions',
        'qr_code_image',
        'min_amount_lkr',
        'max_amount_lkr',
        'requires_proof_upload',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'type' => PaymentMethodType::class,
        'min_amount_lkr' => 'decimal:2',
        'max_amount_lkr' => 'decimal:2',
        'requires_proof_upload' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(HostingOrder::class, 'payment_method_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(HostingPaymentSubmission::class, 'payment_method_id');
    }
}
