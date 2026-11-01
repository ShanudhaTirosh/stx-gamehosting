<?php

namespace BrandedHosting\Platform\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor, HasIcon
{
    case PendingPayment = 'pending_payment';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Provisioning = 'provisioning';
    case Completed = 'completed';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function getLabel(): string
    {
        return match ($this) {
            self::PendingPayment => 'Pending Payment',
            self::UnderReview => 'Under Review',
            self::Approved => 'Approved',
            self::Provisioning => 'Provisioning',
            self::Completed => 'Active / Completed',
            self::Rejected => 'Payment Rejected',
            self::Cancelled => 'Cancelled',
            self::Expired => 'Expired',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PendingPayment => 'warning',
            self::UnderReview => 'info',
            self::Approved => 'primary',
            self::Provisioning => 'info',
            self::Completed => 'success',
            self::Rejected, self::Cancelled, self::Expired => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PendingPayment => 'tabler-clock-pause',
            self::UnderReview => 'tabler-file-search',
            self::Approved => 'tabler-thumb-up',
            self::Provisioning => 'tabler-loader-2',
            self::Completed => 'tabler-circle-check',
            self::Rejected => 'tabler-alert-circle',
            self::Cancelled => 'tabler-ban',
            self::Expired => 'tabler-clock-stop',
        };
    }
}
