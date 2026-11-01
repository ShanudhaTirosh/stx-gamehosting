<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\HostingOrders\Pages;

use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Filament\Admin\Resources\HostingOrders\HostingOrderResource;
use BrandedHosting\Platform\Models\HostingOrder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListHostingOrders extends ListRecords
{
    protected static string $resource = HostingOrderResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders')
                ->badge(HostingOrder::count()),
            'review' => Tab::make('Needs Verification')
                ->badge(HostingOrder::where('status', OrderStatus::UnderReview)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::UnderReview)),
            'approved' => Tab::make('Approved & Deploying')
                ->badge(HostingOrder::whereIn('status', [OrderStatus::Approved, OrderStatus::Provisioning])->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [OrderStatus::Approved, OrderStatus::Provisioning])),
            'completed' => Tab::make('Active Servers')
                ->badge(HostingOrder::where('status', OrderStatus::Completed)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::Completed)),
            'rejected' => Tab::make('Rejected')
                ->badge(HostingOrder::where('status', OrderStatus::Rejected)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::Rejected)),
        ];
    }
}
