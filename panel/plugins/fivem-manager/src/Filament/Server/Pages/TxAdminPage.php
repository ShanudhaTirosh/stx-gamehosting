<?php

namespace BrandedHosting\FiveM\Filament\Server\Pages;

use App\Models\EggVariable;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\FiveM\Services\FiveMService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class TxAdminPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-dashboard';

    protected static ?string $navigationLabel = 'txAdmin';

    protected static ?string $slug = 'fivem-txadmin';

    protected static ?int $navigationSort = 3;

    protected string $view = 'fivem-manager::txadmin';

    public array $txInfo = [];

    public string $serverUuid = '';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'FIVEM';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $this->serverUuid = $server->uuid_short;
        $this->txInfo = FiveMService::getTxAdminDetails($server);
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

    public function toggleTxAdmin(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $current = $this->txInfo['is_enabled'] ?? true;
        $newValue = $current ? '0' : '1';

        $eggVar = EggVariable::where('egg_id', $server->egg_id)
            ->where('env_variable', 'TXADMIN_ENABLE')
            ->first();

        if ($eggVar) {
            ServerVariable::updateOrCreate(
                ['server_id' => $server->id, 'variable_id' => $eggVar->id],
                ['variable_value' => $newValue]
            );

            $this->txInfo = FiveMService::getTxAdminDetails($server);

            Notification::make()
                ->title($newValue === '1' ? 'txAdmin Enabled' : 'txAdmin Disabled')
                ->body('Configuration updated. Restart your server to apply.')
                ->success()
                ->send();
        }
    }

    public function getTitle(): string
    {
        return 'FiveM txAdmin Web Panel';
    }
}
