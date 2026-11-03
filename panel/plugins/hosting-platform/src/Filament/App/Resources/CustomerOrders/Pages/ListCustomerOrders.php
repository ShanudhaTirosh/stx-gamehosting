<?php

namespace BrandedHosting\Platform\Filament\App\Resources\CustomerOrders\Pages;

use BrandedHosting\Platform\Filament\App\Resources\CustomerOrders\CustomerOrdersResource;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListCustomerOrders extends ListRecords
{
    protected static string $resource = CustomerOrdersResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'My Orders & Billing';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('my_servers')
                ->label('My Game Servers')
                ->icon('tabler-server-2')
                ->color('gray')
                ->url(fn () => Filament::getPanel('app')->getUrl()),
            Action::make('order_server')
                ->label('Deploy New Server')
                ->icon('tabler-plus')
                ->color('success')
                ->url('/games'),
        ];
    }
}
