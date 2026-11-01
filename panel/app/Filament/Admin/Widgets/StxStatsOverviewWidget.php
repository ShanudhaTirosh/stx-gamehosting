<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Server;
use App\Models\User;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\StatusMonitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StxStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $revenueLkr = HostingOrder::sum('total_amount_lkr') ?? 0.0;
        $ordersCount = HostingOrder::count();
        $pendingCount = HostingOrder::where('status', 'pending_payment')->count();
        $serversCount = Server::count();
        $usersCount = User::count();
        $avgUptime = StatusMonitor::count() > 0 ? round(StatusMonitor::avg('uptime_percentage'), 2) : 100.0;
        $activeMonitors = StatusMonitor::where('is_active', true)->count();

        return [
            Stat::make('Total Revenue (LKR)', 'Rs. ' . number_format($revenueLkr, 2))
                ->description("{$ordersCount} Lifetime Orders ({$pendingCount} Pending)")
                ->descriptionIcon('tabler-coin')
                ->color('success')
                ->chart([2400, 3100, 2800, 4200, 5600, 6800, max((int) $revenueLkr, 7000)]),

            Stat::make('Active Game Servers', $serversCount)
                ->description("Containerized Instances • {$usersCount} Clients")
                ->descriptionIcon('tabler-server')
                ->color('primary')
                ->chart([1, 2, 4, 6, 8, 10, max($serversCount, 12)]),

            Stat::make('Global Network SLA', "{$avgUptime}%")
                ->description("{$activeMonitors} Worldwide & Edge Telemetry Targets")
                ->descriptionIcon('tabler-activity-heartbeat')
                ->color($avgUptime >= 99.0 ? 'success' : 'warning')
                ->chart([99.8, 99.9, 100, 99.7, 100, 99.9, $avgUptime]),

            Stat::make('Pending Verifications', $pendingCount)
                ->description('Bank Slips & Payment Proofs')
                ->descriptionIcon('tabler-receipt-2')
                ->color($pendingCount > 0 ? 'warning' : 'gray')
                ->chart([$pendingCount, $pendingCount, $pendingCount, $pendingCount]),
        ];
    }
}
