<?php

namespace App\Filament\Admin\Pages;

use App\Enums\TablerIcon;
use App\Filament\Admin\Widgets\StxClusterTelemetryWidget;
use App\Filament\Admin\Widgets\StxQuickActionsWidget;
use App\Filament\Admin\Widgets\StxRecentOrdersWidget;
use App\Filament\Admin\Widgets\StxStatsOverviewWidget;
use App\Services\Helpers\SoftwareVersionService;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = TablerIcon::LayoutDashboard;

    private SoftwareVersionService $softwareVersionService;

    public function mount(SoftwareVersionService $softwareVersionService): void
    {
        $this->softwareVersionService = $softwareVersionService;
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'lg' => 12,
            'xl' => 12,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StxStatsOverviewWidget::class,
            StxClusterTelemetryWidget::class,
            StxQuickActionsWidget::class,
            StxRecentOrdersWidget::class,
        ];
    }

    public function getHeading(): string
    {
        return 'STX Enterprises — Operations Command Center';
    }

    public function getSubheading(): string
    {
        return 'Cloud & Game Server Infrastructure • Automated Telemetry & Billing Orchestration';
    }
}
