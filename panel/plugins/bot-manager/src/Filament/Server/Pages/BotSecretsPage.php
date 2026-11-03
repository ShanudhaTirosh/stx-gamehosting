<?php

namespace BrandedHosting\BotManager\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use BrandedHosting\BotManager\Services\BotService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class BotSecretsPage extends Page
{
    use BlockAccessInConflict;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-key';

    protected static ?string $navigationLabel = 'Environment & Secrets';

    protected static ?string $slug = 'bot-secrets';

    protected static ?int $navigationSort = 1;

    protected string $view = 'bot-manager::secrets';

    public array $variables = [];

    public string $newKey = '';

    public string $newValue = '';

    public array $revealedKeys = [];

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'BOT TOOLS';
    }

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $this->variables = BotService::getEnvVariables($server);
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

    public function toggleReveal(string $key): void
    {
        if (in_array($key, $this->revealedKeys, true)) {
            $this->revealedKeys = array_values(array_filter($this->revealedKeys, fn ($k) => $k !== $key));
        } else {
            $this->revealedKeys[] = $key;
        }
    }

    public function isRevealed(string $key): bool
    {
        return in_array($key, $this->revealedKeys, true);
    }

    public function addVariable(): void
    {
        $key = trim(strtoupper(str_replace(' ', '_', $this->newKey)));
        $value = trim($this->newValue);

        if (empty($key)) {
            Notification::make()->title('Key name cannot be empty')->danger()->send();
            return;
        }

        // Check if key already exists
        $index = -1;
        foreach ($this->variables as $i => $item) {
            if ($item['key'] === $key) {
                $index = $i;
                break;
            }
        }

        $isSecret = str_contains($key, 'TOKEN')
            || str_contains($key, 'SECRET')
            || str_contains($key, 'PASSWORD')
            || str_contains($key, 'KEY')
            || str_contains($key, 'AUTH');

        if ($index >= 0) {
            $this->variables[$index]['value'] = $value;
            $this->variables[$index]['is_secret'] = $isSecret;
        } else {
            $this->variables[] = [
                'key' => $key,
                'value' => $value,
                'is_secret' => $isSecret,
            ];
        }

        $this->newKey = '';
        $this->newValue = '';
        $this->save();
    }

    public function quickAdd(string $presetKey): void
    {
        $this->newKey = $presetKey;
    }

    public function deleteVariable(string $key): void
    {
        $this->variables = array_values(array_filter($this->variables, fn ($item) => $item['key'] !== $key));
        $this->save();
    }

    public function save(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $success = BotService::saveEnvVariables($server, $this->variables);

        if ($success) {
            Notification::make()
                ->title('Environment Saved')
                ->body('.env updated successfully. Restart your bot for new variables to take effect.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Save Failed')
                ->body('Unable to update .env on server.')
                ->danger()
                ->send();
        }
    }

    public function getTitle(): string
    {
        return 'Bot Environment & Secrets (.env)';
    }
}

