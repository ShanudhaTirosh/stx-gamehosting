<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingCategories;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingCategories\Pages\ManageHostingCategories;
use BrandedHosting\Platform\Models\HostingCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HostingCategoryResource extends Resource
{
    protected static ?string $model = HostingCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-category';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Information')
                    ->components([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->placeholder('e.g. Minecraft Hosting or Hytale Dedicated')
                            ->required(),
                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->placeholder('e.g. minecraft')
                            ->unique(ignoreRecord: true),
                        TextInput::make('icon')
                            ->label('Tabler Icon Class')
                            ->placeholder('tabler-brand-minecraft')
                            ->default('tabler-server'),
                        TextInput::make('banner_image')
                            ->label('Category Banner / Artwork URL')
                            ->placeholder('e.g. /images/plans/minecraft.svg'),
                        TextInput::make('badge_text')
                            ->label('Storefront Badge Text')
                            ->placeholder('e.g. Popular or New Game'),
                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Brief overview of the hosting category')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_visible')
                            ->label('Visible on Public Storefront')
                            ->default(true),
                    ])->columns(2),

                Section::make('Search Engine Optimization (SEO) & OpenGraph')
                    ->description('Category-specific meta tags for search engine results.')
                    ->components([
                        TextInput::make('meta_title')
                            ->label('SEO Meta Title')
                            ->placeholder('e.g. High-Performance Minecraft Hosting - Low Ping Sri Lanka'),
                        Textarea::make('meta_description')
                            ->label('SEO Meta Description')
                            ->placeholder('e.g. Fast, reliable Minecraft servers with DDR5 RAM and NVMe Gen4 storage.')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('meta_keywords')
                            ->label('SEO Keywords')
                            ->placeholder('e.g. minecraft hosting, game servers sri lanka, cheap minecraft vps'),
                        TextInput::make('og_image')
                            ->label('OpenGraph / Social Card Image URL')
                            ->placeholder('/images/plans/minecraft.svg'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->fontFamily('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->fontFamily('mono'),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products'),
                TextColumn::make('badge_text')
                    ->label('Badge')
                    ->badge()
                    ->placeholder('None'),
                IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Sort')
                    ->sortable(),
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
            'index' => Pages\ManageHostingCategories::route('/'),
        ];
    }
}
