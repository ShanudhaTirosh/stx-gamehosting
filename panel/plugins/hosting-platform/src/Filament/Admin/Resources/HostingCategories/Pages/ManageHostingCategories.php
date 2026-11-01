<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingCategories\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingCategories\HostingCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHostingCategories extends ManageRecords
{
    protected static string $resource = HostingCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
