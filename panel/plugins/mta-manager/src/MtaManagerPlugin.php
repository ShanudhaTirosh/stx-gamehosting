<?php

namespace BrandedHosting\MtaManager;

use Filament\Contracts\Plugin;
use Filament\Panel;

class MtaManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'mta-manager';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        if (file_exists(plugin_path($this->getId(), "src/Filament/$id/Pages"))) {
            $panel->discoverPages(plugin_path($this->getId(), "src/Filament/$id/Pages"), "BrandedHosting\\MtaManager\\Filament\\$id\\Pages");
        }
    }

    public function boot(Panel $panel): void {}
}

