<?php

namespace BrandedHosting\FiveM\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\FiveM\Services\FiveMService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class ArtifactsPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-world';

    protected static ?string $navigationLabel = 'Artifacts';

    protected static ?string $slug = 'fivem-artifacts';

    protected static ?int $navigationSort = 2;

    protected string $view = 'fivem-manager::artifacts';

    public string $currentVersion = 'recommended';

    public array $artifacts = [];

    public ?string $customArtifact = '';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'FIVEM';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $this->currentVersion = FiveMService::getCurrentArtifactVersion($server);
        $this->artifacts = FiveMService::fetchArtifacts();
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

    public function setVersion(string $version): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $success = FiveMService::setArtifactVersion($server, $version);

        if ($success) {
            $this->currentVersion = $version;

            Notification::make()
                ->title('Artifact Target Updated')
                ->body("Target version set to '{$version}'. To apply, restart your server or trigger a re-install if upgrading binary files.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Update Failed')
                ->body('Unable to update FIVEM_VERSION startup variable.')
                ->danger()
                ->send();
        }
    }

    public function applyCustom(): void
    {
        $version = trim($this->customArtifact ?? '');
        if (empty($version)) {
            Notification::make()->title('Please enter a valid build number')->warning()->send();

            return;
        }

        $this->setVersion($version);
    }

    public function getTitle(): string
    {
        return 'FiveM FXServer Artifacts';
    }
}
