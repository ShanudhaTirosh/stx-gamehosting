<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\StatusIncidents\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\StatusIncidents\StatusIncidentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusIncidents extends ManageRecords
{
    protected static string $resource = StatusIncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
