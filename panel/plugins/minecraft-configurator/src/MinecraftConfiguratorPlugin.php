<?php

namespace BrandedHosting\MinecraftConfigurator;

use Filament\Contracts\Plugin;
use Filament\Panel;

class MinecraftConfiguratorPlugin implements Plugin
{
    public function getId(): string
    {
        return 'minecraft-configurator';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        if (file_exists(plugin_path($this->getId(), "src/Filament/$id/Pages"))) {
            $panel->discoverPages(plugin_path($this->getId(), "src/Filament/$id/Pages"), "BrandedHosting\\MinecraftConfigurator\\Filament\\$id\\Pages");
        }
    }

    public function boot(Panel $panel): void {}
}

