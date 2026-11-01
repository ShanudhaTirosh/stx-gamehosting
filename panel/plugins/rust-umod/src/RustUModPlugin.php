<?php

namespace Boy132\RustUMod;

use Filament\Contracts\Plugin;
use Filament\Panel;

class RustUModPlugin implements Plugin
{
    public function getId(): string
    {
        return 'rust-umod';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        $panel->discoverPages(plugin_path($this->getId(), "src/Filament/$id/Pages"), "Boy132\\RustUMod\\Filament\\$id\\Pages");
    }

    public function boot(Panel $panel): void {}
}
