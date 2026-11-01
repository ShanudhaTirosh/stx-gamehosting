<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingProducts;

use App\Models\Egg;
use BrandedHosting\Platform\Filament\Admin\Resources\HostingProducts\Pages\ManageHostingProducts;
use BrandedHosting\Platform\Models\HostingCategory;
use BrandedHosting\Platform\Models\HostingProduct;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HostingProductResource extends Resource
{
    protected static ?string $model = HostingProduct::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-cube';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->components([
                        Select::make('category_id')
                            ->label('Hosting Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->preload(),
                        TextInput::make('name')
                            ->label('Product Name')
                            ->placeholder('e.g. Paper Minecraft or FiveM FXServer')
                            ->required(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true),
                        TextInput::make('icon')
                            ->label('Tabler Icon')
                            ->default('tabler-server'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Pelican Egg & Infrastructure Binding')
                    ->components([
                        Select::make('egg_id')
                            ->label('Linked Pelican Egg')
                            ->options(fn () => Egg::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->helperText('Select the container egg that handles installation & startup'),
                        TextInput::make('docker_image')
                            ->label('Custom Docker Image (Optional)')
                            ->placeholder('Leave blank to use Egg default image'),
                        TextInput::make('startup_command')
                            ->label('Custom Startup Command (Optional)')
                            ->placeholder('Leave blank to use Egg default startup'),
                        TagsInput::make('deployment_tags')
                            ->label('Required Node Deployment Tags')
                            ->placeholder('e.g. game-node, mc-tier1')
                            ->helperText('Servers will only be provisioned on nodes having these tags'),
                        TagsInput::make('ports')
                            ->label('Reserved Port Requirements')
                            ->placeholder('e.g. 25565 or range 2456-2458'),
                    ])->columns(2),

                Section::make('Status')
                    ->components([
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Active for Sale')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->fontFamily('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge(),
                TextColumn::make('egg.name')
                    ->label('Linked Egg')
                    ->placeholder('No Egg Linked'),
                TextColumn::make('plans_count')
                    ->counts('plans')
                    ->label('Plans'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHostingProducts::route('/'),
        ];
    }
}
