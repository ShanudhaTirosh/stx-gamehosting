<?php

namespace BrandedHosting\BotManager;

use Filament\Contracts\Plugin;
use Filament\Panel;

class BotManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'bot-manager';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        if (file_exists(plugin_path($this->getId(), "src/Filament/$id/Pages"))) {
            $panel->discoverPages(plugin_path($this->getId(), "src/Filament/$id/Pages"), "BrandedHosting\\BotManager\\Filament\\$id\\Pages");
        }
    }

    public function boot(Panel $panel): void {}
}

