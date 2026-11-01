<?php

namespace BrandedHosting\Platform;

use Filament\Contracts\Plugin;
use Filament\Panel;

class HostingPlatformPlugin implements Plugin
{
    public function getId(): string
    {
        return 'hosting-platform';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        if ($panel->getId() === 'admin') {
            $panel->discoverResources(
                plugin_path($this->getId(), 'src/Filament/Admin/Resources'),
                'BrandedHosting\\Platform\\Filament\\Admin\\Resources'
            );
        }

        if ($panel->getId() === 'app') {
            $panel->discoverResources(
                plugin_path($this->getId(), 'src/Filament/App/Resources'),
                'BrandedHosting\\Platform\\Filament\\App\\Resources'
            );
        }
    }

    public function boot(Panel $panel): void {}
}
