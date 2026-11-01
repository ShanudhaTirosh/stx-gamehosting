<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingProducts\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingProducts\HostingProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHostingProducts extends ManageRecords
{
    protected static string $resource = HostingProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
