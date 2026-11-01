<?php

namespace Boy132\RoboAvatars;

use Filament\Contracts\Plugin;
use Filament\Panel;

class RoboAvatarsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'robo-avatars';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
