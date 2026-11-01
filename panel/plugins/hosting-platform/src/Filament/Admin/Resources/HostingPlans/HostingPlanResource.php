<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingPlans;

use BrandedHosting\Platform\Enums\BillingPeriod;
use BrandedHosting\Platform\Filament\Admin\Resources\HostingPlans\Pages\ManageHostingPlans;
use BrandedHosting\Platform\Models\HostingPlan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HostingPlanResource extends Resource
{
    protected static ?string $model = HostingPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-tags';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?string $navigationLabel = 'Hosting Plans & SEO';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Definition')
                    ->components([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->required()
                            ->preload(),
                        TextInput::make('name')
                            ->label('Plan Tier Name')
                            ->placeholder('e.g. Diamond Tier (4GB)')
                            ->required(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true),
                        TextInput::make('tagline')
                            ->label('Marketing Tagline')
                            ->placeholder('e.g. Best for 20+ Players with Mods'),
                    ])->columns(2),

                Section::make('Plan Media & Visual Artwork')
                    ->description('Custom illustrations, branding banners, badge ribbons, and marketing feature highlights.')
                    ->components([
                        TextInput::make('image_url')
                            ->label('Plan Card Image / Artwork URL')
                            ->placeholder('e.g. /images/plans/minecraft.svg or https://...')
                            ->helperText('Direct URL to SVG/PNG graphic. If left empty, system automatically uses category default artwork.'),
                        FileUpload::make('image')
                            ->label('Upload Custom Image File')
                            ->image()
                            ->directory('hosting/plans')
                            ->visibility('public'),
                        TextInput::make('badge')
                            ->label('Card Ribbon / Badge Text')
                            ->placeholder('e.g. Most Popular, Best Value, Ultra Performance')
                            ->helperText('Appears prominently on the top ribbon of the plan card.'),
                        TextInput::make('icon')
                            ->label('Tabler Icon Class')
                            ->placeholder('e.g. ti-sword, ti-brand-minecraft, ti-server-2')
                            ->default('ti-server-2'),
                        Select::make('banner_color')
                            ->label('Theme Accent Color')
                            ->options([
                                'indigo' => 'Indigo Glow (Default)',
                                'purple' => 'Royal Purple',
                                'emerald' => 'Emerald Green',
                                'amber' => 'Cyberpunk Amber',
                                'cyan' => 'Sky Cyan',
                                'rose' => 'Neon Rose',
                            ])
                            ->default('indigo'),
                        TagsInput::make('features')
                            ->label('Feature Bullet Highlights')
                            ->placeholder('Type feature and press Enter (e.g. Free Subdomain)')
                            ->helperText('Highlights listed on the storefront plan card. If blank, auto-generates from hardware specs.'),
                    ])->columns(2),

                Section::make('Search Engine Optimization (SEO) & Social Sharing')
                    ->description('Targeted meta tags, search engine indexing, and social preview cards for this tier.')
                    ->components([
                        TextInput::make('meta_title')
                            ->label('SEO Meta Title')
                            ->placeholder('e.g. Diamond 4GB Minecraft Server Hosting - Low Latency Sri Lanka')
                            ->helperText('Optimal length: 50-60 characters.'),
                        Textarea::make('meta_description')
                            ->label('SEO Meta Description')
                            ->placeholder('e.g. High-performance Minecraft Diamond Tier with 4GB RAM, NVMe Gen4 storage, DDoS protection, and local Sri Lankan payment rails.')
                            ->rows(3)
                            ->helperText('Optimal length: 140-160 characters. Appears in Google search snippets.'),
                        TagsInput::make('meta_keywords')
                            ->label('SEO Keywords')
                            ->placeholder('Add keyword and press Enter (e.g. minecraft hosting sri lanka)'),
                        TextInput::make('og_image')
                            ->label('OpenGraph / Social Share Image URL')
                            ->placeholder('e.g. /images/plans/minecraft.svg or https://...')
                            ->helperText('Preview thumbnail used when link is shared on Discord, WhatsApp, Facebook, or Twitter.'),
                        Toggle::make('is_search_indexed')
                            ->label('Enable Search Engine Indexing (Google / Bing / DuckDuckGo)')
                            ->default(true),
                    ])->columns(2),

                Section::make('Pricing in Sri Lankan Rupees (LKR)')
                    ->components([
                        TextInput::make('price_lkr')
                            ->label('Recurring Price (LKR)')
                            ->numeric()
                            ->prefix('Rs.')
                            ->required(),
                        TextInput::make('setup_fee_lkr')
                            ->label('One-Time Setup Fee (LKR)')
                            ->numeric()
                            ->default(0.00),
                        Select::make('billing_period')
                            ->label('Billing Cycle')
                            ->options(collect(BillingPeriod::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]))
                            ->default(BillingPeriod::Monthly->value)
                            ->required(),
                    ])->columns(3),

                Section::make('Resource Allocations (Enforced by Docker / Wings)')
                    ->components([
                        TextInput::make('memory_mb')
                            ->label('RAM (Megabytes)')
                            ->numeric()
                            ->suffix('MB')
                            ->default(2048)
                            ->required()
                            ->helperText('1024 MB = 1 GB RAM'),
                        TextInput::make('cpu_percent')
                            ->label('vCPU Allocation')
                            ->numeric()
                            ->suffix('%')
                            ->default(100)
                            ->required()
                            ->helperText('100% = 1 Full Core, 200% = 2 Cores'),
                        TextInput::make('disk_mb')
                            ->label('Disk Space')
                            ->numeric()
                            ->suffix('MB')
                            ->default(15360)
                            ->required()
                            ->helperText('10240 MB = 10 GB NVMe'),
                        TextInput::make('swap_mb')
                            ->label('Swap Memory')
                            ->numeric()
                            ->suffix('MB')
                            ->default(0),
                        TextInput::make('database_limit')
                            ->label('MySQL Databases')
                            ->numeric()
                            ->default(1),
                        TextInput::make('backup_limit')
                            ->label('Backup Snapshots')
                            ->numeric()
                            ->default(2),
                        TextInput::make('allocation_limit')
                            ->label('Network Allocations')
                            ->numeric()
                            ->default(1),
                    ])->columns(4),

                Section::make('Publishing Settings')
                    ->components([
                        Toggle::make('is_featured')
                            ->label('Highlight as Featured Plan on Storefront')
                            ->default(false),
                        Toggle::make('is_available')
                            ->label('Available for Purchase')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('display_image')
                    ->label('Artwork')
                    ->circular(false)
                    ->extraImgAttributes(['class' => 'rounded-lg object-contain w-12 h-8 bg-black/40 p-1']),
                TextColumn::make('name')
                    ->label('Plan')
                    ->fontFamily('bold')
                    ->description(fn (HostingPlan $record): ?string => $record->tagline)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->badge(),
                TextColumn::make('badge')
                    ->label('Ribbon')
                    ->badge()
                    ->color('primary')
                    ->placeholder('Standard'),
                TextColumn::make('price_lkr')
                    ->label('Price (LKR)')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('memory_mb')
                    ->label('RAM')
                    ->formatStateUsing(fn ($state) => round($state / 1024, 1) . ' GB'),
                TextColumn::make('cpu_percent')
                    ->label('CPU')
                    ->formatStateUsing(fn ($state) => $state . '%'),
                TextColumn::make('disk_mb')
                    ->label('Disk')
                    ->formatStateUsing(fn ($state) => round($state / 1024, 1) . ' GB'),
                IconColumn::make('is_search_indexed')
                    ->label('SEO')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                IconColumn::make('is_available')
                    ->label('In Stock')
                    ->boolean(),
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
            'index' => ManageHostingPlans::route('/'),
        ];
    }
}
