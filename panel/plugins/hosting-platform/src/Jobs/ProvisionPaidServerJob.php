<?php

namespace BrandedHosting\Platform\Jobs;

use App\Exceptions\Service\Deployment\NoViableAllocationException;
use App\Exceptions\Service\Deployment\NoViableNodeException;
use App\Helpers\Utilities;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\Objects\DeploymentObject;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\Task;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Servers\ServerCreationService;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Enums\ProvisioningStatus;
use BrandedHosting\Platform\Models\HostingCategoryConfig;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\HostingProvisioningRecord;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

/**
 * Turns an approved order into a real game server.
 *
 * Design rules:
 *  - Always dispatched to the queue, never run inside a web request.
 *  - One run per order at a time (ShouldBeUnique + a row lock on the order).
 *  - Node and allocation selection is delegated to Pelican's own deployment services, so
 *    capacity, tags and port locking behave exactly like the stock panel.
 *  - Never invents infrastructure. If no node can take the server the order is returned to
 *    "Approved" with a clear error so staff can add capacity and press Retry.
 *  - If Wings cannot be reached, Pelican removes the half-created server itself and we retry.
 */
class ProvisionPaidServerJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @var int[] */
    public array $backoff = [30, 120];

    public int $timeout = 300;

    public int $uniqueFor = 900;

    public function __construct(
        public int $orderId
    ) {}

    public function uniqueId(): string
    {
        return 'hosting-provision-order-' . $this->orderId;
    }

    public function handle(ServerCreationService $serverCreationService, DatabaseManagementService $databaseService): void
    {
        $order = $this->claimOrder();
        if (!$order) {
            return;
        }

        $record = HostingProvisioningRecord::firstOrCreate(
            ['order_id' => $order->id],
            ['status' => ProvisioningStatus::Allocating, 'started_at' => now(), 'attempts' => 0]
        );
        $record->increment('attempts');
        $record->update(['status' => ProvisioningStatus::Allocating, 'last_error' => null]);

        try {
            $snapshot = $order->plan_snapshot ?? [];
            $plan = $order->plan;
            $product = $plan?->product;

            // Adopt a server that a previous attempt already created for this order.
            $server = Server::query()->where('external_id', $order->order_number)->first();

            if (!$server) {
                $egg = $this->resolveEgg($snapshot, $product?->egg_id);
                $environment = $this->buildEnvironment($egg);

                $memory = (int) (Arr::get($snapshot, 'memory_mb') ?? $plan?->memory_mb ?? 2048);
                $cpu = (int) (Arr::get($snapshot, 'cpu_percent') ?? $plan?->cpu_percent ?? 100);
                $disk = (int) (Arr::get($snapshot, 'disk_mb') ?? $plan?->disk_mb ?? 10240);
                $swap = (int) (Arr::get($snapshot, 'swap_mb') ?? $plan?->swap_mb ?? 0);
                $io = (int) (Arr::get($snapshot, 'io_weight') ?? $plan?->io_weight ?? 500);

                $catConfig = $product?->category?->serverConfig
                    ?? ($product?->category_id ? HostingCategoryConfig::where('category_id', $product->category_id)->first() : null);

                $databaseLimit = $catConfig
                    ? ($catConfig->databases_enabled ? (int) ($catConfig->database_limit ?? 2) : 0)
                    : (int) (Arr::get($snapshot, 'database_limit') ?? $plan?->database_limit ?? 1);
                $allocationLimit = $catConfig
                    ? (int) ($catConfig->allocation_limit ?? 2)
                    : (int) (Arr::get($snapshot, 'allocation_limit') ?? $plan?->allocation_limit ?? 1);
                $backupLimit = $catConfig
                    ? ($catConfig->backups_enabled ? (int) ($catConfig->backup_limit ?? 3) : 0)
                    : (int) (Arr::get($snapshot, 'backup_limit') ?? $plan?->backup_limit ?? 2);

                $requestedName = trim((string) Arr::get($order->server_configuration ?? [], 'requested_name', ''));
                $serverName = Str::limit(
                    ($requestedName !== '' ? $requestedName : ($product?->name ?? 'Game Server')) . ' (' . $order->order_number . ')',
                    190,
                    ''
                );

                $deployment = new DeploymentObject();
                $deployment->setDedicated(false);
                $deployment->setTags($this->deploymentTags($order));
                $deployment->setPorts($this->deploymentPorts($snapshot));

                $record->update(['status' => ProvisioningStatus::Building]);

                // Pelican picks a viable node + free allocation and rolls the server back
                // itself if the daemon is unreachable.
                $server = $serverCreationService->handle([
                    'external_id' => $order->order_number,
                    'name' => $serverName,
                    'description' => "Provisioned via order {$order->order_number} ({$plan?->name})",
                    'owner_id' => $order->user_id,
                    'egg_id' => $egg->id,
                    'image' => Arr::get($snapshot, 'docker_image') ?: ($product?->docker_image ?: Arr::first($egg->docker_images)),
                    'startup' => Arr::get($snapshot, 'startup_command') ?: ($product?->startup_command ?: Arr::first($egg->startup_commands)),
                    'environment' => $environment,
                    'memory' => $memory,
                    'swap' => $swap,
                    'disk' => $disk,
                    'io' => $io,
                    'cpu' => $cpu,
                    'database_limit' => $databaseLimit,
                    'allocation_limit' => $allocationLimit,
                    'backup_limit' => $backupLimit,
                    'start_on_completion' => true,
                    'skip_scripts' => false,
                    'oom_killer' => false,
                ], $deployment);

                // The subdomains plugin adds this column; the stock service does not know about it.
                if (Schema::hasColumn('servers', 'subdomain_limit')) {
                    $subdomainLimit = $catConfig && $catConfig->subdomains_enabled ? (int) $catConfig->subdomain_limit : 1;
                    $server->forceFill(['subdomain_limit' => $subdomainLimit])->save();
                }
            }

            $warnings = [];
            $catConfig ??= $order->plan?->product?->category?->serverConfig;

            $warnings = array_merge(
                $warnings,
                $this->createDatabase($server, $catConfig, $databaseService),
                $this->createBackupSchedule($server, $catConfig)
            );

            $order->update([
                'server_id' => $server->id,
                'status' => OrderStatus::Completed,
                'provisioned_at' => now(),
            ]);

            $record->update([
                'server_id' => $server->id,
                'node_id' => $server->node_id,
                'allocation_id' => $server->allocation_id,
                'status' => ProvisioningStatus::Succeeded,
                'completed_at' => now(),
                'last_error' => null,
                'execution_log' => $warnings ? ['warnings' => $warnings] : null,
            ]);

            Log::info("[Provisioning] Order {$order->order_number} -> server #{$server->id} ({$server->uuid_short}).");
        } catch (NoViableNodeException|NoViableAllocationException $e) {
            // Not a transient error: retrying immediately will not add capacity.
            $this->recordFailure($order, $record, $e);
            $this->fail($e);
        } catch (Throwable $e) {
            $this->recordFailure($order, $record, $e);

            throw $e;
        }
    }

    /**
     * Called by the queue after the final attempt failed.
     */
    public function failed(Throwable $exception): void
    {
        HostingOrder::query()
            ->whereKey($this->orderId)
            ->where('status', OrderStatus::Provisioning->value)
            ->update(['status' => OrderStatus::Approved]);
    }

    /**
     * Lock the order row and move it to Provisioning. Returns null when there is nothing to do.
     */
    protected function claimOrder(): ?HostingOrder
    {
        return DB::transaction(function () {
            /** @var HostingOrder|null $order */
            $order = HostingOrder::query()->whereKey($this->orderId)->lockForUpdate()->first();

            if (!$order) {
                Log::error("[Provisioning] Order #{$this->orderId} not found.");

                return null;
            }

            if ($order->server_id !== null || $order->status === OrderStatus::Completed) {
                Log::info("[Provisioning] Order {$order->order_number} already has a server; skipping.");

                return null;
            }

            // Only paid-and-approved orders may be provisioned. Provisioning is allowed here so a
            // crashed worker can be retried.
            if (!in_array($order->status, [OrderStatus::Approved, OrderStatus::Provisioning], true)) {
                Log::warning("[Provisioning] Order {$order->order_number} is {$order->status->value}; refusing to provision.");

                return null;
            }

            $order->update(['status' => OrderStatus::Provisioning]);

            return $order->load(['user', 'plan.product.category.serverConfig']);
        });
    }

    protected function resolveEgg(array $snapshot, ?int $productEggId): Egg
    {
        $eggId = Arr::get($snapshot, 'egg_id') ?? $productEggId;
        if (!$eggId) {
            throw new Exception('This product has no egg assigned. Set one on the product before deploying.');
        }

        $egg = Egg::with('variables')->find($eggId);
        if (!$egg) {
            throw new Exception("Egg #{$eggId} no longer exists. Re-assign an egg on the product.");
        }

        return $egg;
    }

    /**
     * @return array<string, string>
     */
    protected function buildEnvironment(Egg $egg): array
    {
        $fallbacks = (array) config('hosting-platform.provisioning.env_fallbacks', []);
        $environment = [];
        $missing = [];

        foreach ($egg->variables as $variable) {
            $rules = is_array($variable->rules) ? $variable->rules : (json_decode($variable->rules ?? '[]', true) ?: []);
            $value = $variable->default_value;

            if (($value === null || $value === '') && array_key_exists($variable->env_variable, $fallbacks)) {
                $value = $fallbacks[$variable->env_variable];
            }

            if (($value === null || $value === '') && in_array('required', $rules, true)) {
                $missing[] = $variable->env_variable;
            }

            $environment[$variable->env_variable] = (string) ($value ?? '');
        }

        if ($missing) {
            throw new Exception(
                'Egg "' . $egg->name . '" has required variables with no default: ' . implode(', ', $missing)
                . '. Give them defaults on the egg or add them to hosting-platform.provisioning.env_fallbacks.'
            );
        }

        return $environment;
    }

    /**
     * Node tags this order may be placed on. The checkout "region" value maps to a node tag.
     *
     * @return string[]
     */
    protected function deploymentTags(HostingOrder $order): array
    {
        $region = (string) Arr::get($order->server_configuration ?? [], 'location', 'default');
        $regionTags = (array) config('hosting-platform.provisioning.regions', []);

        if ($region !== 'default' && isset($regionTags[$region])) {
            return (array) $regionTags[$region];
        }

        return (array) config('hosting-platform.provisioning.default_node_tags', ['game-node']);
    }

    /**
     * @return string[]
     */
    protected function deploymentPorts(array $snapshot): array
    {
        $ports = Arr::get($snapshot, 'ports');
        if (is_string($ports) && $ports !== '') {
            return array_values(array_filter(array_map('trim', explode(',', $ports))));
        }
        if (is_array($ports)) {
            return array_values(array_filter(array_map(fn ($p) => trim((string) $p), $ports)));
        }

        // Default game port range, configurable per installation.
        return (array) config('hosting-platform.provisioning.default_ports', ['25565-25999']);
    }

    /**
     * @return string[] warnings (non-fatal)
     */
    protected function createDatabase(Server $server, ?HostingCategoryConfig $catConfig, DatabaseManagementService $databaseService): array
    {
        $wanted = $catConfig ? ($catConfig->databases_enabled && $catConfig->auto_create_database) : true;
        if (!$wanted || $server->database_limit < 1 || $server->databases()->exists()) {
            return [];
        }

        $host = $catConfig?->database_host_id ? DatabaseHost::find($catConfig->database_host_id) : DatabaseHost::query()->first();
        if (!$host) {
            return ['No database host is configured, so no database was created. Add one under Admin > Database Hosts.'];
        }

        try {
            $suffix = preg_replace('/[^A-Za-z0-9_]/', '', (string) ($catConfig?->default_database_prefix ?: 'primary')) ?: 'primary';

            $databaseService->setValidateDatabaseLimit(false)->create($server, [
                'database' => "s{$server->id}_{$suffix}",
                'database_host_id' => $host->id,
                'remote' => (string) config('hosting-platform.provisioning.database_remote', '%'),
            ]);

            return [];
        } catch (Throwable $e) {
            Log::warning("[Provisioning] Database creation failed for server #{$server->id}: " . $e->getMessage());

            return ['Automatic database creation failed: ' . $e->getMessage()];
        }
    }

    /**
     * @return string[] warnings (non-fatal)
     */
    protected function createBackupSchedule(Server $server, ?HostingCategoryConfig $catConfig): array
    {
        $wanted = $catConfig ? ($catConfig->backups_enabled && $catConfig->auto_backup_enabled) : true;
        if (!$wanted || $server->backup_limit < 1 || $server->schedules()->exists()) {
            return [];
        }

        try {
            $cron = preg_split('/\s+/', trim($catConfig?->auto_backup_cron ?: '0 4 * * *'));
            [$minute, $hour, $dom, $month, $dow] = array_pad($cron, 5, '*');
            $timezone = $catConfig?->auto_backup_timezone ?: 'UTC';

            DB::transaction(function () use ($server, $minute, $hour, $dom, $month, $dow, $timezone) {
                $schedule = Schedule::create([
                    'server_id' => $server->id,
                    'name' => 'Automated Daily Backup',
                    'cron_minute' => $minute,
                    'cron_hour' => $hour,
                    'cron_day_of_month' => $dom,
                    'cron_month' => $month,
                    'cron_day_of_week' => $dow,
                    'timezone' => $timezone,
                    'is_active' => true,
                    'is_processing' => false,
                    'only_when_online' => false,
                    'next_run_at' => Utilities::getScheduleNextRunDate($minute, $hour, $dom, $month, $dow, $timezone),
                ]);

                Task::create([
                    'schedule_id' => $schedule->id,
                    'sequence_id' => 1,
                    'action' => 'backup',
                    'payload' => '',
                    'time_offset' => 0,
                    'is_queued' => false,
                    'continue_on_failure' => true,
                ]);
            });

            return [];
        } catch (Throwable $e) {
            Log::warning("[Provisioning] Backup schedule failed for server #{$server->id}: " . $e->getMessage());

            return ['Automatic backup schedule failed: ' . $e->getMessage()];
        }
    }

    protected function recordFailure(HostingOrder $order, HostingProvisioningRecord $record, Throwable $e): void
    {
        Log::error("[Provisioning Failed] Order {$order->order_number}: " . $e->getMessage(), ['exception' => $e]);

        $record->update([
            'status' => ProvisioningStatus::Failed,
            'last_error' => Str::limit($e->getMessage(), 1000),
            'execution_log' => [
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        // Put the order back where staff can see and retry it.
        $order->update(['status' => OrderStatus::Approved]);
    }
}
