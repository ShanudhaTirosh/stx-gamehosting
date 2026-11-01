<?php

namespace Boy132\GenericOIDCProviders;

use Filament\Contracts\Plugin;
use Filament\Panel;

class GenericOIDCProvidersPlugin implements Plugin
{
    public function getId(): string
    {
        return 'generic-oidc-providers';
    }

    public function register(Panel $panel): void
    {
        $id = str($panel->getId())->title();

        $panel->discoverResources(plugin_path($this->getId(), "src/Filament/$id/Resources"), "Boy132\\GenericOIDCProviders\\Filament\\$id\\Resources");
    }

    public function boot(Panel $panel): void {}
}
