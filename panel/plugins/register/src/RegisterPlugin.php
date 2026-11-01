<?php

namespace Boy132\Register;

use Boy132\Register\Filament\Pages\Auth\Register;
use Filament\Contracts\Plugin;
use Filament\Panel;

class RegisterPlugin implements Plugin
{
    public function getId(): string
    {
        return 'register';
    }

    public function register(Panel $panel): void
    {
        $panel->registration(Register::class);
    }

    public function boot(Panel $panel): void {}
}
