<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingSeoSettings\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingSeoSettings\HostingSeoSettingResource;
use BrandedHosting\Platform\Models\HostingSeoSetting;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageHostingSeoSettings extends ManageRecords
{
    protected static string $resource = HostingSeoSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_sitemap')
                ->label('View XML Sitemap')
                ->icon('tabler-sitemap')
                ->color('info')
                ->url('/sitemap.xml', shouldOpenInNewTab: true),

            Action::make('view_robots')
                ->label('View robots.txt')
                ->icon('tabler-file-text')
                ->color('gray')
                ->url('/robots.txt', shouldOpenInNewTab: true),

            Action::make('clear_seo_cache')
                ->label('Purge SEO Cache')
                ->icon('tabler-refresh')
                ->color('warning')
                ->action(function () {
                    HostingSeoSetting::clearCache();
                    Notification::make()
                        ->title('SEO Cache Cleared')
                        ->body('Global SEO configuration cache has been refreshed.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
