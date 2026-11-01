<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingPaymentMethods;

use BrandedHosting\Platform\Enums\PaymentMethodType;
use BrandedHosting\Platform\Filament\Admin\Resources\HostingPaymentMethods\Pages\ManageHostingPaymentMethods;
use BrandedHosting\Platform\Models\HostingPaymentMethod;
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

class HostingPaymentMethodResource extends Resource
{
    protected static ?string $model = HostingPaymentMethod::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Hosting Platform';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Method Details')
                    ->components([
                        TextInput::make('name')
                            ->label('Display Name')
                            ->placeholder('e.g. Commercial Bank Transfer or Dialog eZ Cash')
                            ->required(),
                        TextInput::make('code')
                            ->label('Internal Code')
                            ->placeholder('e.g. bank_transfer')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label('Payment Type')
                            ->options(collect(PaymentMethodType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]))
                            ->required(),
                        Textarea::make('description')
                            ->label('Customer Summary')
                            ->placeholder('Short description shown on checkout')
                            ->rows(2),
                    ])->columns(2),

                Section::make('Recipient & Account Instructions (Sri Lanka)')
                    ->components([
                        TextInput::make('recipient_name')
                            ->label('Account / Recipient Name')
                            ->placeholder('e.g. Hosting Company (Pvt) Ltd or Merchant Name'),
                        TextInput::make('account_or_mobile_number')
                            ->label('Account or Mobile Number')
                            ->placeholder('e.g. 1000293847 or 0771234567'),
                        TextInput::make('bank_name')
                            ->label('Bank Name (If Bank Transfer)')
                            ->placeholder('e.g. Commercial Bank of Ceylon PLC'),
                        TextInput::make('branch_name')
                            ->label('Branch Name')
                            ->placeholder('e.g. Colombo Fort Branch'),
                        Textarea::make('payment_instructions')
                            ->label('Full Payment Instructions')
                            ->placeholder("Step 1: Transfer exact amount.\nStep 2: Note down reference.\nStep 3: Upload slip screenshot.")
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Controls & Limits')
                    ->components([
                        TextInput::make('min_amount_lkr')
                            ->label('Min Amount (LKR)')
                            ->numeric()
                            ->default(0.00),
                        TextInput::make('sort_order')
                            ->label('Display Sort Order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('requires_proof_upload')
                            ->label('Require Proof Slip Upload')
                            ->default(true),
                        Toggle::make('is_active')
                            ->label('Active / Visible to Customers')
                            ->default(true),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Method')
                    ->fontFamily('bold')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('account_or_mobile_number')
                    ->label('Account / Mobile')
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->placeholder('-'),
                IconColumn::make('requires_proof_upload')
                    ->label('Proof Req.')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageHostingPaymentMethods::route('/'),
        ];
    }
}
