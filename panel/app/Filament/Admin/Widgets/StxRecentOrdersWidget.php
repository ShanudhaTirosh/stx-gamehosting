<?php

namespace App\Filament\Admin\Widgets;

use BrandedHosting\Platform\Models\HostingOrder;
use Filament\Widgets\Widget;

class StxRecentOrdersWidget extends Widget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.stx_recent_orders';

    public function getViewData(): array
    {
        $orders = HostingOrder::with(['plan', 'user'])->latest()->limit(5)->get();

        return [
            'orders' => $orders,
        ];
    }
}
