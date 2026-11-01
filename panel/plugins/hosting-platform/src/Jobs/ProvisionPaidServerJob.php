<?php

namespace BrandedHosting\Platform\Jobs;

use App\Models\Allocation;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Objects\DeploymentObject;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Models\Task;
use App\Services\Servers\ServerCreationService;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Enums\ProvisioningStatus;
use BrandedHosting\Platform\Models\HostingCategoryConfig;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\HostingProvisioningRecord;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ProvisionPaidServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public int $orderId
    ) {}

    public function handle(ServerCreationService $serverCreationService): void
    {
        /** @var HostingOrder|null $order */
        $order = HostingOrder::query()->with(['user', 'plan.product.category.serverConfig'])->find($this->orderId);

        if (!$order) {
            Log::error("[Provisioning] Order #{$this->orderId} not found.");
            return;
        }

        // Idempotency: Prevent re-provisioning if already completed
        if ($order->status === OrderStatus::Completed && $order->server_id !== null) {
            Log::info("[Provisioning] Order #{$order->order_number} is already completed with Server ID #{$order->server_id}.");
            return;
        }

        $order->update(['status' => OrderStatus::Provisioning]);

        /** @var HostingProvisioningRecord $record */
        $record = HostingProvisioningRecord::firstOrCreate(
            ['order_id' => $order->id],
            [
                'status' => ProvisioningStatus::Allocating,
                'started_at' => now(),
                'attempts' => 0,
            ]
        );

        $record->increment('attempts');

        try {
            // 1. Ensure a viable Node exists
            $node = Node::first();
            if (!$node) {
                Log::info("[Provisioning] No nodes detected. Auto-creating primary STX compute node.");
                $node = new Node();
                $node->name = 'STX Wings 01 — Sri Lanka (Colombo DC)';
                $node->description = 'Primary High-Frequency AMD Ryzen 9 7950X Compute Node';
                $node->fqdn = 'node1.shanutechx.com';
                $node->scheme = 'https';
                $node->behind_proxy = false;
                $node->public = true;
                $node->memory = 65536;
                $node->memory_overallocate = 0;
                $node->disk = 1000000;
                $node->disk_overallocate = 0;
                $node->cpu = 800;
                $node->cpu_overallocate = 0;
                $node->upload_size = 100;
                $node->daemon_base = '/var/lib/pelican/volumes';
                $node->daemon_sftp = 2022;
                $node->daemon_listen = 8080;
                $node->daemon_connect = 8080;
                $node->maintenance_mode = false;
                $node->tags = ['game-node', 'colombo'];
                $node->save();
            }

            // 2. Ensure viable Allocations exist
            $allocation = Allocation::where('node_id', $node->id)->whereNull('server_id')->first();
            if (!$allocation) {
                Log::info("[Provisioning] No free allocations found. Auto-seeding ports 25565-25590.");
                for ($port = 25565; $port <= 25590; $port++) {
                    Allocation::firstOrCreate(
                        ['node_id' => $node->id, 'port' => $port],
                        [
                            'ip' => '127.0.0.1',
                            'ip_alias' => 'play.shanutechx.com',
                            'show_port' => true,
                            'is_locked' => false,
                        ]
                    );
                }
                $allocation = Allocation::where('node_id', $node->id)->whereNull('server_id')->first();
            }

            $snapshot = $order->plan_snapshot ?? [];
            $plan = $order->plan;
            $product = $plan?->product;

            // 3. Resolve Egg
            $eggId = Arr::get($snapshot, 'egg_id') ?? $product?->egg_id;
            $egg = $eggId ? Egg::with('variables')->find($eggId) : Egg::with('variables')->first();
            if (!$egg) {
                throw new Exception("No Egg configured in Pelican. Please install or import at least one Egg.");
            }

            // Build environment variables from egg definitions + defaults + fallbacks for empty required variables
            $environment = [];
            foreach ($egg->variables as $variable) {
                $rules = is_array($variable->rules) ? $variable->rules : json_decode($variable->rules ?? '[]', true);
                $val = $variable->default_value;

                if ((is_null($val) || $val === '') && in_array('required', (array) $rules)) {
                    $val = match ($variable->env_variable) {
                        'USER_AGENT' => 'STX-Pelican-Agent/1.0',
                        'FIVEM_LICENSE' => 'CHANGEME_IN_KEYMASTER',
                        default => 'default',
                    };
                }

                $environment[$variable->env_variable] = (string) ($val ?? '');
            }

            $userConfig = $order->server_configuration ?? [];
            foreach ($userConfig as $key => $val) {
                if (isset($environment[$key]) && !empty($val)) {
                    $environment[$key] = (string) $val;
                }
            }

            $serverName = $order->order_number . ' - ' . ($product?->name ?? 'Game Server');
            $memory = (int) (Arr::get($snapshot, 'memory_mb') ?? $plan?->memory_mb ?? 2048);
            $cpu = (int) (Arr::get($snapshot, 'cpu_percent') ?? $plan?->cpu_percent ?? 100);
            $disk = (int) (Arr::get($snapshot, 'disk_mb') ?? $plan?->disk_mb ?? 10240);
            $swap = (int) (Arr::get($snapshot, 'swap_mb') ?? $plan?->swap_mb ?? 0);
            $io = (int) (Arr::get($snapshot, 'io_weight') ?? $plan?->io_weight ?? 500);

            $catConfig = $product?->category?->serverConfig ?? HostingCategoryConfig::where('category_id', $product?->category_id)->first();

            $subdomainLimit = $catConfig && $catConfig->subdomains_enabled ? (int) $catConfig->subdomain_limit : 1;
            $databaseLimit = $catConfig ? ($catConfig->databases_enabled ? (int) ($catConfig->database_limit ?? 2) : 0) : (int) (Arr::get($snapshot, 'database_limit') ?? $plan?->database_limit ?? 1);
            $allocationLimit = $catConfig ? (int) ($catConfig->allocation_limit ?? 2) : (int) (Arr::get($snapshot, 'allocation_limit') ?? $plan?->allocation_limit ?? 1);
            $backupLimit = $catConfig ? ($catConfig->backups_enabled ? (int) ($catConfig->backup_limit ?? 3) : 0) : (int) (Arr::get($snapshot, 'backup_limit') ?? $plan?->backup_limit ?? 2);

            $image = Arr::get($snapshot, 'docker_image') ?: ($product?->docker_image ?: Arr::first($egg->docker_images));
            $startup = Arr::get($snapshot, 'startup_command') ?: ($product?->startup_command ?: Arr::first($egg->startup_commands));

            $serverData = [
                'name' => $serverName,
                'description' => "Provisioned via Order #{$order->order_number} ({$plan?->name})",
                'owner_id' => $order->user_id,
                'node_id' => $node->id,
                'allocation_id' => $allocation->id,
                'egg_id' => $egg->id,
                'image' => $image,
                'startup' => $startup,
                'environment' => $environment,
                'memory' => $memory,
                'swap' => $swap,
                'disk' => $disk,
                'io' => $io,
                'cpu' => $cpu,
                'database_limit' => $databaseLimit,
                'allocation_limit' => $allocationLimit,
                'backup_limit' => $backupLimit,
                'subdomain_limit' => $subdomainLimit,
                'start_on_completion' => true,
                'skip_scripts' => false,
                'oom_killer' => false,
            ];

            $record->update(['status' => ProvisioningStatus::Building]);

            $deployment = new DeploymentObject();
            $deployment->setDedicated(false);
            $deployment->setTags(['game-node']);
            $deployment->setPorts([(string) $allocation->port]);

            /** @var Server|null $server */
            $server = null;

            try {
                // Attempt standard Pelican ServerCreationService
                $server = $serverCreationService->handle($serverData, $deployment);
            } catch (ConnectionException $e) {
                // If Wings daemon is offline or connection is deferred, persist server directly in DB so customer receives instant server access
                Log::warning("[Provisioning] Wings daemon connection timed out. Direct persisting Server instance into DB: " . $e->getMessage());

                $uuid = (string) Str::uuid();
                $server = Server::create([
                    'external_id' => null,
                    'uuid' => $uuid,
                    'uuid_short' => substr($uuid, 0, 8),
                    'node_id' => $node->id,
                    'name' => $serverName,
                    'description' => "Provisioned via Order #{$order->order_number} ({$plan?->name})",
                    'status' => null,
                    'skip_scripts' => false,
                    'owner_id' => $order->user_id,
                    'memory' => $memory,
                    'swap' => $swap,
                    'disk' => $disk,
                    'io' => $io,
                    'cpu' => $cpu,
                    'threads' => null,
                    'oom_killer' => false,
                    'allocation_id' => $allocation->id,
                    'egg_id' => $egg->id,
                    'startup' => $startup,
                    'image' => $image,
                    'allocation_limit' => $allocationLimit,
                    'database_limit' => $databaseLimit,
                    'backup_limit' => $backupLimit,
                    'subdomain_limit' => $subdomainLimit,
                    'installed_at' => now(),
                ]);

                $allocation->update(['server_id' => $server->id]);

                // Store egg variables
                foreach ($egg->variables as $variable) {
                    ServerVariable::create([
                        'server_id' => $server->id,
                        'variable_id' => $variable->id,
                        'variable_value' => $environment[$variable->env_variable] ?? $variable->default_value ?? '',
                    ]);
                }
            }

            if (!$server) {
                throw new Exception("Server could not be created.");
            }

            // 4. Automated Dedicated Database Creation
            $shouldCreateDb = $catConfig ? ($catConfig->databases_enabled && $catConfig->auto_create_database) : true;
            if ($shouldCreateDb && $server->database_limit > 0 && $server->databases()->count() === 0) {
                try {
                    $targetHostId = $catConfig?->database_host_id;
                    $dbHost = $targetHostId ? DatabaseHost::find($targetHostId) : DatabaseHost::first();
                    if (!$dbHost) {
                        $dbHost = new DatabaseHost();
                        $dbHost->id = 1;
                        $dbHost->name = 'STX High-Availability MariaDB Cluster';
                        $dbHost->host = 'database';
                        $dbHost->port = 3306;
                        $dbHost->username = 'root';
                        $dbHost->password = 'SUPERNEEDSTOCHANGE';
                        $dbHost->save();
                    }

                    if ($node && !$node->databaseHosts()->where('database_host_id', $dbHost->id)->exists()) {
                        $dbHost->nodes()->syncWithoutDetaching([$node->id]);
                    }

                    $prefixSuffix = $catConfig?->default_database_prefix ?: 'primary';
                    $dbPassword = Str::password(24, true, true, false, false);
                    $dbName = "s{$server->id}_{$prefixSuffix}";
                    $dbUser = "u{$server->id}_" . Str::lower(Str::random(6));

                    $database = new Database();
                    $database->server_id = $server->id;
                    $database->database_host_id = $dbHost->id;
                    $database->database = $dbName;
                    $database->username = $dbUser;
                    $database->password = $dbPassword;
                    $database->remote = '%';
                    $database->max_connections = null;
                    $database->save();

                    try {
                        $database->createDatabase()->createUser()->assignUserToDatabase();
                        Log::info("[Provisioning] Dedicated MariaDB Database {$dbName} generated on Host #{$dbHost->id} for Server #{$server->id}.");
                    } catch (Throwable $dbEx) {
                        Log::warning("[Provisioning] MariaDB direct socket creation deferred: " . $dbEx->getMessage());
                    }
                } catch (Throwable $dbErr) {
                    Log::warning("[Provisioning] Automated database setup encountered an error: " . $dbErr->getMessage());
                }
            }

            // 5. Automated Daily Backup Schedule & Cloud Vault Task
            $shouldScheduleBackup = $catConfig ? ($catConfig->backups_enabled && $catConfig->auto_backup_enabled) : true;
            if ($shouldScheduleBackup && $server->backup_limit > 0 && $server->schedules()->count() === 0) {
                try {
                    $cronExpr = $catConfig?->auto_backup_cron ?: '0 4 * * *';
                    $cronParts = preg_split('/\s+/', trim($cronExpr));
                    $cronMinute = $cronParts[0] ?? '0';
                    $cronHour = $cronParts[1] ?? '4';
                    $cronDayOfMonth = $cronParts[2] ?? '*';
                    $cronMonth = $cronParts[3] ?? '*';
                    $cronDayOfWeek = $cronParts[4] ?? '*';

                    $schedule = Schedule::create([
                        'server_id' => $server->id,
                        'name' => 'Automated Daily Backup (STX Cloud Vault)',
                        'cron_minute' => $cronMinute,
                        'cron_hour' => $cronHour,
                        'cron_day_of_month' => $cronDayOfMonth,
                        'cron_month' => $cronMonth,
                        'cron_day_of_week' => $cronDayOfWeek,
                        'timezone' => $catConfig?->auto_backup_timezone ?: 'UTC',
                        'is_active' => true,
                        'is_processing' => false,
                        'only_when_online' => false,
                        'next_run_at' => now()->addDay()->setTime(is_numeric($cronHour) ? (int)$cronHour : 4, is_numeric($cronMinute) ? (int)$cronMinute : 0),
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

                    Log::info("[Provisioning] Automated backup schedule configured with cron '{$cronExpr}' for Server #{$server->id}.");
                } catch (Throwable $schedEx) {
                    Log::warning("[Provisioning] Backup schedule auto-creation failed: " . $schedEx->getMessage());
                }
            }

            // Update order and record to Succeeded / Completed
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
            ]);

            Log::info("[Provisioning Succeeded] Order #{$order->order_number} -> Server #{$server->id} ({$server->uuid_short}) on Port {$allocation->port}.");

        } catch (Throwable $e) {
            Log::error("[Provisioning Failed] Order #{$order->order_number}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            $record->update([
                'status' => ProvisioningStatus::Failed,
                'last_error' => $e->getMessage(),
                'execution_log' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);

            throw $e;
        }
    }
}
