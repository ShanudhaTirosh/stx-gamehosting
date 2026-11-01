<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingSeoSettings;

use BrandedHosting\Platform\Filament\Admin\Resources\HostingSeoSettings\Pages\ManageHostingSeoSettings;
use BrandedHosting\Platform\Models\HostingSeoSetting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HostingSeoSettingResource extends Resource
{
    protected static ?string $model = HostingSeoSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-world-www';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?string $navigationLabel = 'Storefront & SEO Settings';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Search Engine Optimization (SEO) & Title Architecture')
                    ->description('Global meta tags, title formatting, search snippet descriptions, and indexing policies.')
                    ->components([
                        TextInput::make('site_name')
                            ->label('Platform Brand Name')
                            ->default('STX Enterprises')
                            ->required(),
                        TextInput::make('site_tagline')
                            ->label('Brand Tagline')
                            ->default('Enterprise Cloud & Game Server Infrastructure')
                            ->required(),
                        TextInput::make('title_separator')
                            ->label('Title Separator')
                            ->default('—')
                            ->helperText('Character between page title and brand name (e.g. — or |)'),
                        TextInput::make('meta_title_template')
                            ->label('Meta Title Pattern')
                            ->default('{title} {separator} {site_name}')
                            ->helperText('Supported tags: {title}, {separator}, {site_name}, {tagline}'),
                        Textarea::make('meta_description')
                            ->label('Default Global Meta Description')
                            ->rows(3)
                            ->helperText('Appears in Google/Bing search snippets when a page does not specify a custom description.')
                            ->columnSpanFull(),
                        Textarea::make('meta_keywords')
                            ->label('Global SEO Keywords')
                            ->rows(2)
                            ->helperText('Comma-separated keywords for meta tags.')
                            ->columnSpanFull(),
                        TextInput::make('og_image')
                            ->label('Default OpenGraph & Social Preview Image')
                            ->default('/images/plans/default-server.svg')
                            ->helperText('Image displayed when your website or links are shared on Discord, WhatsApp, or Facebook.'),
                        TextInput::make('twitter_handle')
                            ->label('Twitter / X Author Handle')
                            ->default('@STXEnterprises')
                            ->placeholder('@STXEnterprises'),
                        Select::make('robots_policy')
                            ->label('Search Engine Crawling Policy')
                            ->options([
                                'index, follow' => 'Index & Follow (Production Mode — Recommended)',
                                'noindex, nofollow' => 'No-Index (Development Mode — Block Crawlers)',
                            ])
                            ->default('index, follow')
                            ->required(),
                    ])->columns(2),

                Section::make('Search Engine Verification & Analytics')
                    ->description('Meta tag tokens to verify ownership in Google Search Console and Bing Webmaster.')
                    ->components([
                        TextInput::make('google_site_verification')
                            ->label('Google Search Console Verification Token')
                            ->placeholder('e.g. d_J8k2L9mQ4n... (content value from google-site-verification)'),
                        TextInput::make('bing_site_verification')
                            ->label('Bing Webmaster Verification Code')
                            ->placeholder('e.g. 8A3F124... (msvalidate.01 token)'),
                        TextInput::make('analytics_tag_id')
                            ->label('Google Analytics Measurement ID / Cloudflare Token')
                            ->placeholder('e.g. G-XXXXXXXXXX'),
                        Textarea::make('custom_head_scripts')
                            ->label('Custom Header Scripts (<head>)')
                            ->placeholder('<!-- Global site tags, custom CSS, or tracking pixels -->')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('custom_body_scripts')
                            ->label('Custom Footer Scripts (before </body>)')
                            ->placeholder('<!-- Live chat widgets, feedback scripts -->')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Company Profile, NOC Desk & Social Channels')
                    ->description('Contact channels and social communities surfaced in headers, footers, and Structured Data.')
                    ->components([
                        TextInput::make('company_legal_name')
                            ->label('Company Legal Registered Name')
                            ->default('STX Enterprises Lanka (Pvt) Ltd')
                            ->required(),
                        TextInput::make('contact_email')
                            ->label('Official Support Email')
                            ->default('support@shanutechx.com')
                            ->required(),
                        TextInput::make('contact_phone')
                            ->label('NOC Operations Hotline')
                            ->default('+94 11 234 5678'),
                        TextInput::make('support_discord_url')
                            ->label('Discord Community Invite')
                            ->default('https://discord.gg/stxenterprises'),
                        TextInput::make('support_whatsapp_url')
                            ->label('WhatsApp Direct Helpdesk URL')
                            ->placeholder('https://chat.whatsapp.com/...'),
                        TextInput::make('status_page_url')
                            ->label('Network Status Page URL')
                            ->default('/status'),
                    ])->columns(2),

                Section::make('XML Sitemap & Structured Data (JSON-LD)')
                    ->description('Rich snippets for Google search results and automatic sitemap publishing.')
                    ->components([
                        Toggle::make('sitemap_enabled')
                            ->label('Enable Dynamic XML Sitemap at /sitemap.xml')
                            ->default(true),
                        Toggle::make('schema_org_enabled')
                            ->label('Enable Schema.org (Organization, WebSite & Product Offers)')
                            ->default(true),
                        TextInput::make('schema_price_range')
                            ->label('Schema Price Range')
                            ->default('LKR 1,500 - 15,000'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')
                    ->label('Platform Brand')
                    ->fontFamily('bold'),
                TextColumn::make('site_tagline')
                    ->label('Tagline')
                    ->limit(40),
                TextColumn::make('robots_policy')
                    ->label('Crawler Policy')
                    ->badge()
                    ->color(fn ($state) => str_contains($state, 'noindex') ? 'danger' : 'success'),
                IconColumn::make('sitemap_enabled')
                    ->label('Sitemap')
                    ->boolean(),
                IconColumn::make('schema_org_enabled')
                    ->label('JSON-LD')
                    ->boolean(),
                TextColumn::make('contact_email')
                    ->label('Contact')
                    ->badge(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHostingSeoSettings::route('/'),
        ];
    }
}
