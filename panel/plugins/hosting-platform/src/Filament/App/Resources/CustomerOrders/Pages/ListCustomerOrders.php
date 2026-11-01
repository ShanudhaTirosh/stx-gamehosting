<?php

namespace BrandedHosting\Platform\Filament\App\Resources\CustomerOrders\Pages;

use BrandedHosting\Platform\Filament\App\Resources\CustomerOrders\CustomerOrdersResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListCustomerOrders extends ListRecords
{
    protected static string $resource = CustomerOrdersResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'My Orders & Billing';
    }
}
