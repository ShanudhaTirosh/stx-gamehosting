<?php

namespace BrandedHosting\MinecraftConfigurator\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\MinecraftConfigurator\Services\MinecraftPropertiesService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class MinecraftPropertiesPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-settings-cog';

    protected static ?string $navigationLabel = 'Server Properties';

    protected static ?string $slug = 'minecraft-properties';

    protected static ?int $navigationSort = 3;

    protected string $view = 'minecraft-configurator::properties';

    public string $motd = '';

    public string $gamemode = 'survival';

    public string $difficulty = 'normal';

    public bool $onlineMode = true;

    public bool $pvp = true;

    public int $maxPlayers = 20;

    public int $viewDistance = 10;

    public int $simulationDistance = 10;

    public bool $whitelist = false;

    public bool $hardcore = false;

    public bool $commandBlocks = true;

    public bool $allowFlight = false;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'MINECRAFT';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $props = MinecraftPropertiesService::getProperties($server);

        $this->motd = $props['motd'] ?? '';
        $this->gamemode = $props['gamemode'] ?? 'survival';
        $this->difficulty = $props['difficulty'] ?? 'normal';
        $this->onlineMode = (bool) ($props['online-mode'] ?? true);
        $this->pvp = (bool) ($props['pvp'] ?? true);
        $this->maxPlayers = (int) ($props['max-players'] ?? 20);
        $this->viewDistance = (int) ($props['view-distance'] ?? 10);
        $this->simulationDistance = (int) ($props['simulation-distance'] ?? 10);
        $this->whitelist = (bool) ($props['white-list'] ?? false);
        $this->hardcore = (bool) ($props['hardcore'] ?? false);
        $this->commandBlocks = (bool) ($props['enable-command-block'] ?? true);
        $this->allowFlight = (bool) ($props['allow-flight'] ?? false);
    }

    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return parent::canAccess() && MinecraftPropertiesService::isMinecraftServer($server);
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return static::canAccess();
    }

    public function save(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        MinecraftPropertiesService::saveProperties($server, [
            'motd' => trim($this->motd),
            'gamemode' => $this->gamemode,
            'difficulty' => $this->difficulty,
            'online-mode' => $this->onlineMode,
            'pvp' => $this->pvp,
            'max-players' => max(1, min(10000, $this->maxPlayers)),
            'view-distance' => max(3, min(32, $this->viewDistance)),
            'simulation-distance' => max(3, min(32, $this->simulationDistance)),
            'white-list' => $this->whitelist,
            'hardcore' => $this->hardcore,
            'enable-command-block' => $this->commandBlocks,
            'allow-flight' => $this->allowFlight,
        ]);

        Notification::make()
            ->title('Properties Saved')
            ->body('server.properties updated successfully. Restart server to apply changes.')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Minecraft Server Properties Configurator';
    }
}

