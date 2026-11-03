<?php

namespace BrandedHosting\Platform\Filament\App\Resources\CustomerOrders;

use App\Models\User;
use BrandedHosting\Platform\Filament\App\Resources\CustomerOrders\Pages\ListCustomerOrders;
use BrandedHosting\Platform\Models\HostingOrder;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomerOrdersResource extends Resource
{
    protected static ?string $model = HostingOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-receipt-2';

    protected static ?string $navigationLabel = 'My Orders & Billing';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'My Orders & Billing';

    protected static ?string $slug = 'customer-orders';

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        if (!auth()->check()) {
            return false;
        }

        /** @var User $user */
        $user = auth()->user();

        return $user->isRootAdmin() || $record->user_id === $user->id;
    }

    public static function getEloquentQuery(): Builder
    {
        if (!auth()->check()) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        /** @var User $user */
        $user = auth()->user();

        // Strictly isolate customer orders to the authenticated user
        return parent::getEloquentQuery()->where('user_id', $user->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->description(fn (HostingOrder $order) => $order->plan?->product?->name),
                TextColumn::make('total_amount_lkr')
                    ->label('Total (LKR)')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format((float) $state, 2)),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('server.name')
                    ->label('Active Server')
                    ->placeholder('Pending Build')
                    ->icon('tabler-brand-docker')
                    ->url(fn (HostingOrder $order) => $order->server ? "/server/{$order->server->uuid_short}" : null),
                TextColumn::make('created_at')
                    ->label('Ordered On')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view_invoice')
                    ->label('Invoice')
                    ->icon('tabler-receipt')
                    ->color('gray')
                    ->url(fn (HostingOrder $order) => route('hosting.app.order.invoice', ['uuid' => $order->uuid]))
                    ->openUrlInNewTab(),
                Action::make('track_order')
                    ->label('Order Details & Payment')
                    ->icon('tabler-external-link')
                    ->color('primary')
                    ->url(fn (HostingOrder $order) => route('hosting.app.order.tracking', ['uuid' => $order->uuid])),
                Action::make('console')
                    ->label('Open Console')
                    ->icon('tabler-terminal-2')
                    ->color('success')
                    ->visible(fn (HostingOrder $order) => $order->server !== null)
                    ->url(fn (HostingOrder $order) => "/server/{$order->server?->uuid_short}"),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerOrders::route('/'),
        ];
    }
}
