<?php

namespace BrandedHosting\Platform\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProvisioningStatus: string implements HasLabel, HasColor, HasIcon
{
    case Pending = 'pending';
    case Allocating = 'allocating';
    case Building = 'building';
    case Succeeded = 'succeeded';
    case Failed = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Queued for Provisioning',
            self::Allocating => 'Allocating Node & Ports',
            self::Building => 'Building Server Container',
            self::Succeeded => 'Provisioning Succeeded',
            self::Failed => 'Provisioning Failed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending, self::Allocating, self::Building => 'info',
            self::Succeeded => 'success',
            self::Failed => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'tabler-clock',
            self::Allocating => 'tabler-server',
            self::Building => 'tabler-cpu',
            self::Succeeded => 'tabler-check',
            self::Failed => 'tabler-alert-triangle',
        };
    }
}
