<?php

namespace BrandedHosting\FiveM\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\FiveM\Services\FiveMService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class GameBuildPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-world';

    protected static ?string $navigationLabel = 'GameBuild';

    protected static ?string $slug = 'fivem-gamebuild';

    protected static ?int $navigationSort = 1;

    protected string $view = 'fivem-manager::gamebuild';

    public ?string $activeBuild = null;

    public array $builds = [];

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'FIVEM';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $this->builds = FiveMService::getGameBuilds();
        $this->activeBuild = FiveMService::getActiveGameBuild($server);
    }

    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return parent::canAccess() && FiveMService::isFiveMServer($server);
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return static::canAccess();
    }

    public function activate(string $buildNumber): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $success = FiveMService::setGameBuild($server, $buildNumber);

        if ($success) {
            $this->activeBuild = $buildNumber;

            Notification::make()
                ->title('GameBuild Activated')
                ->body("Build {$buildNumber} set in server.cfg. Restart your server for the changes to take effect.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Update Failed')
                ->body('Unable to update server.cfg. Ensure your server files are accessible.')
                ->danger()
                ->send();
        }
    }

    public function getTitle(): string
    {
        return 'FiveM GameBuild Selector';
    }
}
