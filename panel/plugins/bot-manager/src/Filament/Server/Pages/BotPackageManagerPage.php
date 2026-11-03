<?php

namespace BrandedHosting\BotManager\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\BotManager\Services\BotService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class BotPackageManagerPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-package';

    protected static ?string $navigationLabel = 'Package Helper';

    protected static ?string $slug = 'bot-packages';

    protected static ?int $navigationSort = 3;

    protected string $view = 'bot-manager::packages';

    public string $runtime = 'node';

    public array $commonPackages = [];

    public string $customPackage = '';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'BOT TOOLS';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $this->runtime = BotService::getRuntime($server);
        $this->commonPackages = BotService::getCommonPackages($this->runtime);
    }

    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return parent::canAccess() && BotService::isBotServer($server);
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return static::canAccess();
    }

    public function install(string $packageName): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        BotService::installPackage($server, $packageName);

        Notification::make()
            ->title('Package Added')
            ->body("Added {$packageName} to startup packages. Restart your server for automated installation.")
            ->success()
            ->send();
    }

    public function installCustom(): void
    {
        $pkg = trim($this->customPackage);
        if (empty($pkg)) {
            return;
        }

        $this->install($pkg);
        $this->customPackage = '';
    }

    public function getTitle(): string
    {
        return 'Bot Package Helper (' . strtoupper($this->runtime) . ')';
    }
}
