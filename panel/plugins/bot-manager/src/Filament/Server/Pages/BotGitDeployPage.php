<?php

namespace BrandedHosting\BotManager\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\BotManager\Services\BotService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class BotGitDeployPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-brand-git';

    protected static ?string $navigationLabel = 'Git & Deployment';

    protected static ?string $slug = 'bot-deploy';

    protected static ?int $navigationSort = 2;

    protected string $view = 'bot-manager::git-deploy';

    public string $gitAddress = '';

    public string $branch = 'main';

    public bool $autoUpdate = false;

    public string $mainFile = 'index.js';

    public string $runtime = 'node';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'BOT TOOLS';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $this->runtime = BotService::getRuntime($server);
        $status = BotService::getGitStatus($server);

        $this->gitAddress = $status['git_address'];
        $this->branch = $status['branch'];
        $this->autoUpdate = $status['auto_update'];
        $this->mainFile = $status['main_file'];
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

    public function saveSettings(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        BotService::saveGitSettings($server, [
            'git_address' => trim($this->gitAddress),
            'branch' => trim($this->branch) ?: 'main',
            'auto_update' => $this->autoUpdate,
            'main_file' => trim($this->mainFile),
        ]);

        Notification::make()
            ->title('Deploy Configuration Updated')
            ->body('Git repository and boot parameters saved. Changes take effect on next boot.')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Git & Deployment Manager';
    }
}
