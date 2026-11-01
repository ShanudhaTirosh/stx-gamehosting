<?php

namespace BrandedHosting\Platform\Enums;

use Filament\Support\Contracts\HasLabel;

enum BillingPeriod: string implements HasLabel
{
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case SemiAnnually = 'semi_annually';
    case Annually = 'annually';
    case OneTime = 'one_time';

    public function getLabel(): string
    {
        return match ($this) {
            self::Monthly => 'Monthly',
            self::Quarterly => 'Quarterly (3 Months)',
            self::SemiAnnually => 'Semi-Annually (6 Months)',
            self::Annually => 'Annually (1 Year)',
            self::OneTime => 'One-Time Setup',
        };
    }

    public function getDurationInDays(): ?int
    {
        return match ($this) {
            self::Monthly => 30,
            self::Quarterly => 90,
            self::SemiAnnually => 180,
            self::Annually => 365,
            self::OneTime => null,
        };
    }
}
