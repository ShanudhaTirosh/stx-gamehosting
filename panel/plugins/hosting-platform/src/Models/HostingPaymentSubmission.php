<?php

namespace BrandedHosting\Platform\Models;

use App\Models\User;
use BrandedHosting\Platform\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostingPaymentSubmission extends Model
{
    protected $table = 'hosting_payment_submissions';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'amount_paid_lkr',
        'transaction_reference',
        'customer_notes',
        'verification_status',
        'verified_by_user_id',
        'verified_at',
        'staff_decision_notes',
    ];

    protected $casts = [
        'amount_paid_lkr' => 'decimal:2',
        'verification_status' => VerificationStatus::class,
        'verified_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(HostingOrder::class, 'order_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(HostingPaymentMethod::class, 'payment_method_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(HostingPaymentProof::class, 'submission_id');
    }
}
