<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingCategoryConfigs\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingCategoryConfigs\HostingCategoryConfigResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHostingCategoryConfigs extends ManageRecords
{
    protected static string $resource = HostingCategoryConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Category Configuration Profile'),
        ];
    }
}
