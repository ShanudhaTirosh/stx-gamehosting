<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\StatusMonitors;

use BrandedHosting\Platform\Filament\Admin\Resources\StatusMonitors\Pages\ManageStatusMonitors;
use BrandedHosting\Platform\Models\StatusMonitor;
use BrandedHosting\Platform\Services\StatusCheckerService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusMonitorResource extends Resource
{
    protected static ?string $model = StatusMonitor::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-activity-heartbeat';

    protected static string|\UnitEnum|null $navigationGroup = 'Status & Telemetry';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Monitor Configuration')
                    ->components([
                        TextInput::make('name')
                            ->label('Monitor Name')
                            ->placeholder('e.g. STX Edge DNS Resolution')
                            ->required(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true),

                        Select::make('type')
                            ->label('Check Type')
                            ->options([
                                'http' => 'HTTP / HTTPS Web Endpoint',
                                'tcp' => 'TCP Port Socket Probe',
                                'ping' => 'Network / Port Ping',
                                'database' => 'MariaDB SQL Database',
                                'redis' => 'Redis In-Memory Cache',
                                'node' => 'Wings Compute Daemon',
                            ])
                            ->default('http')
                            ->required(),

                        TextInput::make('category')
                            ->label('Category')
                            ->placeholder('Core Services, Edge, Compute Nodes, Payment Rails')
                            ->default('Edge & Web Services')
                            ->required(),

                        TextInput::make('target_url')
                            ->label('Target URL / Host:Port')
                            ->placeholder('https://info.shanutechx.com or 127.0.0.1:8080')
                            ->columnSpanFull(),

                        TextInput::make('expected_status_code')
                            ->label('Expected HTTP Status Code')
                            ->numeric()
                            ->default(200),

                        TextInput::make('check_interval_seconds')
                            ->label('Interval (Seconds)')
                            ->numeric()
                            ->default(60),

                        TextInput::make('timeout_seconds')
                            ->label('Timeout (Seconds)')
                            ->numeric()
                            ->default(5),

                        Toggle::make('is_active')
                            ->label('Active Telemetry')
                            ->default(true),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Monitor Name')
                    ->description(fn (StatusMonitor $record): string => $record->target_url ?: $record->type)
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'http',
                        'warning' => 'tcp',
                        'info' => 'ping',
                        'success' => 'database',
                        'danger' => 'redis',
                        'secondary' => 'node',
                    ]),

                TextColumn::make('category')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('current_status')
                    ->label('Current State')
                    ->badge()
                    ->colors([
                        'success' => 'operational',
                        'warning' => 'degraded',
                        'danger' => 'down',
                        'info' => 'maintenance',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('current_latency_ms')
                    ->label('Latency')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} ms" : '—')
                    ->sortable(),

                TextColumn::make('uptime_percentage')
                    ->label('30d Uptime')
                    ->formatStateUsing(fn ($state) => "{$state}%")
                    ->color(fn ($state) => $state >= 99.0 ? 'success' : ($state >= 95.0 ? 'warning' : 'danger'))
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('last_checked_at')
                    ->label('Last Checked')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('ping')
                    ->label('Ping Now')
                    ->icon('tabler-refresh')
                    ->color('info')
                    ->action(function (StatusMonitor $record) {
                        $checker = new StatusCheckerService();
                        $check = $checker->checkMonitor($record);

                        if ($check->is_ok) {
                            Notification::make()
                                ->title("Probe Success: {$record->name}")
                                ->body("Responded in {$check->latency_ms}ms (Status: {$check->status_code})")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title("Probe Failed: {$record->name}")
                                ->body("Error: {$check->error_message} ({$check->latency_ms}ms)")
                                ->danger()
                                ->send();
                        }
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStatusMonitors::route('/'),
        ];
    }
}
