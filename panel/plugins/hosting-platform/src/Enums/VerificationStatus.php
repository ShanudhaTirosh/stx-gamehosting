<?php

namespace BrandedHosting\Platform\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum VerificationStatus: string implements HasLabel, HasColor, HasIcon
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case InfoRequested = 'info_requested';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending Verification',
            self::Approved => 'Verified & Approved',
            self::Rejected => 'Verification Rejected',
            self::InfoRequested => 'Additional Information Requested',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::InfoRequested => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'tabler-clock',
            self::Approved => 'tabler-circle-check',
            self::Rejected => 'tabler-circle-x',
            self::InfoRequested => 'tabler-help-circle',
        };
    }
}
