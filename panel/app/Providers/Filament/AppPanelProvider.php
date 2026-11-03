<?php

namespace App\Providers\Filament;

use App\Enums\TablerIcon;
use App\Services\Helpers\PluginService;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Panel;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel)
            ->id('app')
            ->default()
            ->path(config('panel.client_path', 'app'))
            ->homeUrl(fn () => Filament::getPanel('app')->getUrl() . '/servers')
            ->breadcrumbs(false)
            ->navigation(false)
            ->topbar(true)
            ->userMenuItems([
                Action::make('servers')
                    ->label('My Game Servers')
                    ->url(fn () => Filament::getPanel('app')->getUrl() . '/servers')
                    ->icon(TablerIcon::Server),
                Action::make('orders')
                    ->label('My Orders & Billing')
                    ->url(fn () => Filament::getPanel('app')->getUrl() . '/customer-orders')
                    ->icon(TablerIcon::Receipt),
                Action::make('storefront')
                    ->label('Browse Storefront')
                    ->url('/')
                    ->icon(TablerIcon::BuildingStore),
                Action::make('status')
                    ->label('Network Status')
                    ->url('/status')
                    ->icon(TablerIcon::ActivityHeartbeat),
                Action::make('to_admin')
                    ->label(trans('profile.admin'))
                    ->url(fn () => Filament::getPanel('admin')->getUrl())
                    ->icon(TablerIcon::ArrowForward)
                    ->visible(fn () => user()?->canAccessPanel(Filament::getPanel('admin'))),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->plugins([
                FilamentLogViewerPlugin::make()
                    ->authorize(false),
            ]);

        /** @var PluginService $pluginService */
        $pluginService = app(PluginService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        $pluginService->loadPanelPlugins($panel);

        return $panel;
    }
}
