<?php

namespace BrandedHosting\Platform\Filament\Admin\Resources\StatusMonitors\Pages;

use BrandedHosting\Platform\Filament\Admin\Resources\StatusMonitors\StatusMonitorResource;
use BrandedHosting\Platform\Services\StatusCheckerService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusMonitors extends ManageRecords
{
    protected static string $resource = StatusMonitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('run_all_checks')
                ->label('Probe All Monitors')
                ->icon('tabler-activity')
                ->color('warning')
                ->action(function () {
                    $checker = new StatusCheckerService();
                    $results = $checker->checkAll();
                    $count = count($results);

                    Notification::make()
                        ->title("Telemetry Probe Completed")
                        ->body("Successfully executed real network checks across {$count} active monitors.")
                        ->success()
                        ->send();
                }),
            Action::make('seed_defaults')
                ->label('Seed STX Monitors')
                ->icon('tabler-database-import')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $checker = new StatusCheckerService();
                    $checker->seedDefaultMonitors();
                    $checker->checkAll();

                    Notification::make()
                        ->title("Default Monitors Seeded")
                        ->body("STX Infrastructure monitors added and probed.")
                        ->success()
                        ->send();
                }),
            Action::make('view_status_page')
                ->label('Public Status Page')
                ->icon('tabler-external-link')
                ->color('success')
                ->url('/status', shouldOpenInNewTab: true),
        ];
    }
}
