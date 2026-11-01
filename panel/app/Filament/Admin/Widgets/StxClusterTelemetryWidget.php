<?php

namespace App\Filament\Admin\Widgets;

use BrandedHosting\Platform\Models\StatusIncident;
use BrandedHosting\Platform\Models\StatusMonitor;
use BrandedHosting\Platform\Services\StatusCheckerService;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class StxClusterTelemetryWidget extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 8,
        'xl' => 8,
    ];

    protected string $view = 'filament.widgets.stx_cluster_telemetry';

    public function pingAll(): void
    {
        $checker = new StatusCheckerService();
        $results = $checker->checkAll();

        Notification::make()
            ->title('Telemetry Scan Finished')
            ->body('Executed real-time probe across all ' . count($results) . ' monitors.')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $monitors = StatusMonitor::where('is_active', true)->orderBy('sort_order')->get();
        $activeIncidents = StatusIncident::active()->with('updates')->get();

        return [
            'monitors' => $monitors,
            'activeIncidents' => $activeIncidents,
        ];
    }
}
