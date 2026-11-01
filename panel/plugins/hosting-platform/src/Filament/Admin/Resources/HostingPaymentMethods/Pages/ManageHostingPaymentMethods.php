<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingPaymentMethods\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingPaymentMethods\HostingPaymentMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHostingPaymentMethods extends ManageRecords
{
    protected static string $resource = HostingPaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
