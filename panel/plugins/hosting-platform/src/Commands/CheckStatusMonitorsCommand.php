<?php

namespace BrandedHosting\Platform\Commands;

use BrandedHosting\Platform\Models\StatusMonitor;
use BrandedHosting\Platform\Services\StatusCheckerService;
use Illuminate\Console\Command;

class CheckStatusMonitorsCommand extends Command
{
    protected $signature = 'hosting:check-monitors {--seed : Seed default monitors if table is empty}';
    protected $description = 'Execute real health checks for all active STX status monitors';

    public function handle(StatusCheckerService $checker): int
    {
        if ($this->option('seed') || StatusMonitor::count() === 0) {
            $this->info('Seeding default STX status monitors...');
            $checker->seedDefaultMonitors();
        }

        $monitors = StatusMonitor::where('is_active', true)->orderBy('sort_order')->get();
        $this->info("Checking {$monitors->count()} monitors...");

        foreach ($monitors as $monitor) {
            $check = $checker->checkMonitor($monitor);
            $statusText = $check->is_ok ? "<fg=green>OK</>" : "<fg=red>FAIL</>";
            $this->line(" - [{$monitor->type}] {$monitor->name}: {$statusText} ({$check->latency_ms}ms) " . ($check->error_message ? "<fg=yellow>[{$check->error_message}]</>" : ""));
        }

        $this->info('All monitor checks completed successfully.');
        return Command::SUCCESS;
    }
}
