<?php

namespace BrandedHosting\MtaManager\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\MtaManager\Services\MtaService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class MtaConfigPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Server Config';

    protected static ?string $slug = 'mta-config';

    protected static ?int $navigationSort = 1;

    protected string $view = 'mta-manager::config';

    public string $servername = '';

    public int $maxplayers = 32;

    public int $fpslimit = 60;

    public bool $voice = true;

    public bool $ase = true;

    public int $httpport = 22005;

    public string $password = '';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'MTA:SA';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $config = MtaService::getMtaConfig($server);

        $this->servername = $config['servername'];
        $this->maxplayers = (int) $config['maxplayers'];
        $this->fpslimit = (int) $config['fpslimit'];
        $this->voice = (bool) $config['voice'];
        $this->ase = (bool) $config['ase'];
        $this->httpport = (int) $config['httpport'];
        $this->password = $config['password'];
    }

    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return parent::canAccess() && MtaService::isMtaServer($server);
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

        MtaService::saveMtaConfig($server, [
            'servername' => trim($this->servername),
            'maxplayers' => max(1, min(1024, $this->maxplayers)),
            'fpslimit' => max(25, min(100, $this->fpslimit)),
            'voice' => $this->voice,
            'ase' => $this->ase,
            'httpport' => $this->httpport,
            'password' => trim($this->password),
        ]);

        Notification::make()
            ->title('MTA Configuration Saved')
            ->body('mtaserver.conf updated. Restart your server for new parameters to apply.')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Multi Theft Auto Server Configurator';
    }
}

