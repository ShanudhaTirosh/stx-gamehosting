<?php

namespace Boy132\PirateLanguage;

use Filament\Contracts\Plugin;
use Filament\Panel;

class PirateLanguagePlugin implements Plugin
{
    public function getId(): string
    {
        return 'pirate-language';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
