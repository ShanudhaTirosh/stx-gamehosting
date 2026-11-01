<?php

namespace BrandedHosting\Platform\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentMethodType: string implements HasLabel, HasColor, HasIcon
{
    case BankTransfer = 'bank_transfer';
    case EzCash = 'ez_cash';
    case HelaPay = 'helapay';

    public function getLabel(): string
    {
        return match ($this) {
            self::BankTransfer => 'Bank Transfer (Commercial / Sampath / BOC / HNB)',
            self::EzCash => 'Dialog eZ Cash (#111# / App)',
            self::HelaPay => 'Helakuru HelaPay (QR / VPA)',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BankTransfer => 'primary',
            self::EzCash => 'warning',
            self::HelaPay => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BankTransfer => 'tabler-building-bank',
            self::EzCash => 'tabler-device-mobile',
            self::HelaPay => 'tabler-qrcode',
        };
    }
}
