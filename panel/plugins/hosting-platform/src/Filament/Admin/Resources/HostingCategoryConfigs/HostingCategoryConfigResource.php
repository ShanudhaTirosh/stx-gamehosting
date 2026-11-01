<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingCategoryConfigs;

use App\Models\BackupHost;
use App\Models\DatabaseHost;
use BrandedHosting\Platform\Filament\Admin\Resources\HostingCategoryConfigs\Pages\ManageHostingCategoryConfigs;
use BrandedHosting\Platform\Models\HostingCategory;
use BrandedHosting\Platform\Models\HostingCategoryConfig;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HostingCategoryConfigResource extends Resource
{
    protected static ?string $model = HostingCategoryConfig::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-server-cog';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?string $navigationLabel = 'Category Server Configs';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Association')
                    ->components([
                        Select::make('category_id')
                            ->label('Hosting Category')
                            ->relationship('category', 'name')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->preload()
                            ->helperText('Select the game or cloud server category to apply this infrastructure configuration profile to.'),
                    ]),

                Section::make('Subdomains Automation Configuration')
                    ->description('Automatic DNS routing and subdomain allocation for servers in this category.')
                    ->components([
                        Toggle::make('subdomains_enabled')
                            ->label('Enable Subdomains for this Category')
                            ->default(true),
                        TextInput::make('subdomain_limit')
                            ->label('Subdomain Limit per Server')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('default_domain')
                            ->label('Base Domain')
                            ->default('shanutechx.com')
                            ->placeholder('e.g. shanutechx.com'),
                        TextInput::make('subdomain_prefix_pattern')
                            ->label('Automated Subdomain Pattern')
                            ->default('{order_ref}.play')
                            ->helperText('Supported placeholders: {order_ref}, {server_id}'),
                        Toggle::make('allow_custom_subdomain')
                            ->label('Allow Customer to Request Custom Subdomain Prefix')
                            ->default(true),
                    ])->columns(2),

                Section::make('Databases (MariaDB & MySQL) Automation Configuration')
                    ->description('Cluster SQL database provisioning and client credentials.')
                    ->components([
                        Toggle::make('databases_enabled')
                            ->label('Enable Databases for this Category')
                            ->default(true),
                        TextInput::make('database_limit')
                            ->label('Default Database Limit per Server')
                            ->numeric()
                            ->default(2)
                            ->required(),
                        Toggle::make('auto_create_database')
                            ->label('Auto-Generate Primary Database upon Server Deployment')
                            ->default(true)
                            ->helperText('Automatically creates s{server_id}_primary with dedicated credentials upon order approval.'),
                        Select::make('database_host_id')
                            ->label('Target Database Cluster Host')
                            ->options(fn () => DatabaseHost::query()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select MariaDB Host (defaults to Primary Cluster)'),
                        TextInput::make('default_database_prefix')
                            ->label('Default Database Name Suffix')
                            ->default('primary')
                            ->helperText('Resulting schema will be s{server_id}_{suffix}'),
                    ])->columns(2),

                Section::make('Backups & Cloud Vault Automation Configuration')
                    ->description('Disaster recovery snapshots and automated daily cron schedule policies.')
                    ->components([
                        Toggle::make('backups_enabled')
                            ->label('Enable Backups for this Category')
                            ->default(true),
                        TextInput::make('backup_limit')
                            ->label('Default Backup Snapshot Quota')
                            ->numeric()
                            ->default(3)
                            ->required(),
                        Toggle::make('auto_backup_enabled')
                            ->label('Auto-Schedule Daily Automated Backups')
                            ->default(true)
                            ->helperText('Automatically creates a cron schedule on the server so customers never have to set up backups manually.'),
                        TextInput::make('auto_backup_cron')
                            ->label('Cron Schedule Timing')
                            ->default('0 4 * * *')
                            ->helperText('Default: 0 4 * * * (Daily at 04:00 UTC)'),
                        Select::make('backup_host_id')
                            ->label('Target Backup Vault Host')
                            ->options(fn () => BackupHost::query()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select Backup Host (defaults to Local Wings Vault)'),
                    ])->columns(2),

                Section::make('Network Allocations & Port Pool Configuration')
                    ->description('Port assignment rules and secondary port availability.')
                    ->components([
                        TextInput::make('allocation_limit')
                            ->label('Max Port Allocations per Server')
                            ->numeric()
                            ->default(2)
                            ->required(),
                        Toggle::make('auto_assign_ports')
                            ->label('Auto-Allocate Primary Port upon Deployment')
                            ->default(true),
                        TextInput::make('default_port')
                            ->label('Default Game Port (Optional)')
                            ->numeric()
                            ->placeholder('e.g. 25565'),
                        TextInput::make('port_range_start')
                            ->label('Port Pool Range Start')
                            ->numeric()
                            ->default(25565),
                        TextInput::make('port_range_end')
                            ->label('Port Pool Range End')
                            ->numeric()
                            ->default(25590),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->fontFamily('bold')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('subdomains_enabled')
                    ->label('Subdomains')
                    ->boolean(),
                TextColumn::make('subdomain_limit')
                    ->label('Subdomain Limit')
                    ->badge(),
                IconColumn::make('databases_enabled')
                    ->label('Databases')
                    ->boolean(),
                TextColumn::make('database_limit')
                    ->label('DB Limit')
                    ->badge(),
                IconColumn::make('backups_enabled')
                    ->label('Backups')
                    ->boolean(),
                TextColumn::make('backup_limit')
                    ->label('Backup Quota')
                    ->badge(),
                TextColumn::make('auto_backup_cron')
                    ->label('Backup Cron')
                    ->fontFamily('mono')
                    ->badge(),
                TextColumn::make('allocation_limit')
                    ->label('Port Limit')
                    ->badge(),
            ])
            ->actions([
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
            'index' => ManageHostingCategoryConfigs::route('/'),
        ];
    }
}
