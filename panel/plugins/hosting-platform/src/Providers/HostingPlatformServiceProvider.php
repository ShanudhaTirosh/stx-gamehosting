<?php

namespace BrandedHosting\Platform\Providers;

use App\Enums\RolePermissionPrefixes;
use App\Models\Role;
use BrandedHosting\Platform\Console\Commands\CheckStatusMonitorsCommand;
use BrandedHosting\Platform\Console\Commands\ExpireStaleOrdersCommand;
use BrandedHosting\Platform\Console\Commands\RunBillingCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class HostingPlatformServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(HostingRoutesServiceProvider::class);

        $permissions = [];
        foreach (RolePermissionPrefixes::cases() as $prefix) {
            $permissions[] = $prefix->value;
        }
        $permissions[] = 'approvePayment';
        $permissions[] = 'rejectPayment';
        $permissions[] = 'viewPaymentProof';

        Role::registerCustomPermissions([
            'hostingOrder' => $permissions,
            'hostingPlan' => $permissions,
            'hostingProduct' => $permissions,
            'hostingPaymentMethod' => $permissions,
            'statusMonitor' => $permissions,
            'statusIncident' => $permissions,
        ]);

        Role::registerCustomModelIcon('hosting_order', 'tabler-shopping-cart');
        Role::registerCustomModelIcon('hosting_product', 'tabler-cube');
        Role::registerCustomModelIcon('hosting_plan', 'tabler-tags');
        Role::registerCustomModelIcon('hosting_payment_method', 'tabler-credit-card');
        Role::registerCustomModelIcon('hosting_category', 'tabler-category');
        Role::registerCustomModelIcon('status_monitor', 'tabler-activity-heartbeat');
        Role::registerCustomModelIcon('status_incident', 'tabler-alert-triangle');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckStatusMonitorsCommand::class,
                ExpireStaleOrdersCommand::class,
                RunBillingCommand::class,
            ]);
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('hosting:check-monitors')->everyMinute()->withoutOverlapping(2);
            $schedule->command('hosting:expire-orders')->hourly()->withoutOverlapping(10);
            $schedule->command('hosting:run-billing')->hourly()->withoutOverlapping(30);
        });
    }
}
