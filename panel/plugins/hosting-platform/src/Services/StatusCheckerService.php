<?php

namespace BrandedHosting\Platform\Services;

use BrandedHosting\Platform\Models\StatusCheck;
use BrandedHosting\Platform\Models\StatusMonitor;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class StatusCheckerService
{
    /**
     * Run check for a single monitor.
     */
    public function checkMonitor(StatusMonitor $monitor): StatusCheck
    {
        $start = microtime(true);
        $isOk = false;
        $statusCode = null;
        $errorMessage = null;

        try {
            switch ($monitor->type) {
                case 'database':
                    DB::connection()->getPdo()->query('SELECT 1');
                    $isOk = true;
                    $statusCode = 200;
                    break;

                case 'redis':
                    Redis::ping();
                    $isOk = true;
                    $statusCode = 200;
                    break;

                case 'tcp':
                case 'node':
                    $target = $monitor->target_url;
                    $host = $target;
                    $port = 80;

                    if (str_contains($target, '://')) {
                        $parsed = parse_url($target);
                        $host = $parsed['host'] ?? $target;
                        $port = $parsed['port'] ?? ($parsed['scheme'] === 'https' ? 443 : 80);
                    } elseif (str_contains($target, ':')) {
                        $parts = explode(':', $target);
                        $host = $parts[0];
                        $port = (int) ($parts[1] ?? 80);
                    }

                    $errno = 0;
                    $errstr = '';
                    $timeout = max($monitor->timeout_seconds, 3);
                    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);

                    if ($fp) {
                        fclose($fp);
                        $isOk = true;
                        $statusCode = 200;
                    } else {
                        $isOk = false;
                        $errorMessage = "Socket connection failed ($errno): $errstr";
                    }
                    break;

                case 'ping':
                    $host = parse_url($monitor->target_url, PHP_URL_HOST) ?: $monitor->target_url;
                    // Test port 80/443 reachability as standard web ping
                    $errno = 0;
                    $errstr = '';
                    $fp = @fsockopen($host, 443, $errno, $errstr, 2) ?: @fsockopen($host, 80, $errno, $errstr, 2);
                    if ($fp) {
                        fclose($fp);
                        $isOk = true;
                        $statusCode = 200;
                    } else {
                        $isOk = false;
                        $errorMessage = "Host unreachable";
                    }
                    break;

                case 'http':
                default:
                    $url = $monitor->target_url;
                    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $url = 'https://' . $url;
                    }

                    $response = Http::timeout(max($monitor->timeout_seconds, 5))
                        ->withHeaders([
                            'User-Agent' => 'STX-Enterprises-StatusProbe/2.0 (+https://info.shanutechx.com)',
                            'Accept' => '*/*',
                        ])
                        ->get($url);

                    $statusCode = $response->status();
                    $expected = $monitor->expected_status_code ?: 200;

                    if ($statusCode === $expected || ($statusCode >= 200 && $statusCode < 400)) {
                        $isOk = true;
                    } else {
                        $isOk = false;
                        $errorMessage = "HTTP $statusCode (expected $expected)";
                    }
                    break;
            }
        } catch (Exception $e) {
            $isOk = false;
            $errorMessage = substr($e->getMessage(), 0, 500);
        }

        $latency = round((microtime(true) - $start) * 1000, 2);

        // Store check record
        $check = StatusCheck::create([
            'status_monitor_id' => $monitor->id,
            'is_ok' => $isOk,
            'latency_ms' => $latency,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
            'created_at' => now(),
        ]);

        // Evaluate monitor status
        $newStatus = 'operational';
        if (!$isOk) {
            $newStatus = 'down';
        } elseif ($latency > 1500) {
            $newStatus = 'degraded';
        }

        $statusChanged = $monitor->current_status !== $newStatus;

        $monitor->update([
            'current_status' => $newStatus,
            'current_latency_ms' => $latency,
            'last_checked_at' => now(),
            'last_status_change_at' => $statusChanged ? now() : $monitor->last_status_change_at,
        ]);

        $monitor->recalculateUptime(30);

        return $check;
    }

    /**
     * Run checks for all active monitors.
     */
    public function checkAll(): array
    {
        $monitors = StatusMonitor::where('is_active', true)->orderBy('sort_order')->get();
        $results = [];

        foreach ($monitors as $monitor) {
            $results[$monitor->slug] = $this->checkMonitor($monitor);
        }

        return $results;
    }

    /**
     * Ensure default essential monitors exist in the database.
     */
    public function seedDefaultMonitors(): void
    {
        $defaults = [
            [
                'name' => 'STX Info & Docs Portal',
                'slug' => 'stx-info-portal',
                'type' => 'http',
                'target_url' => 'https://info.shanutechx.com',
                'category' => 'Edge & Web Services',
                'expected_status_code' => 200,
                'check_interval_seconds' => 30,
                'timeout_seconds' => 8,
                'sort_order' => 1,
                'description' => 'Official STX Enterprises knowledge base, customer documentation and portal gateway.',
            ],
            [
                'name' => 'STX Core Control Panel (Local)',
                'slug' => 'stx-control-panel',
                'type' => 'http',
                'target_url' => 'http://localhost/status',
                'category' => 'Core Services',
                'expected_status_code' => 200,
                'check_interval_seconds' => 30,
                'timeout_seconds' => 5,
                'sort_order' => 2,
                'description' => 'Main Pelican / STX Game Management Web Application and storefront.',
            ],
            [
                'name' => 'STX High-Availability MariaDB Database',
                'slug' => 'stx-mariadb-cluster',
                'type' => 'database',
                'target_url' => 'database:3306',
                'category' => 'Infrastructure',
                'expected_status_code' => 200,
                'check_interval_seconds' => 30,
                'timeout_seconds' => 3,
                'sort_order' => 3,
                'description' => 'Central ACID transactional database for orders, users, and game server allocations.',
            ],
            [
                'name' => 'STX Distributed Redis Cache & Sessions',
                'slug' => 'stx-redis-cache',
                'type' => 'redis',
                'target_url' => 'cache:6379',
                'category' => 'Infrastructure',
                'expected_status_code' => 200,
                'check_interval_seconds' => 30,
                'timeout_seconds' => 3,
                'sort_order' => 4,
                'description' => 'In-memory Redis instance for caching, queue jobs, and real-time session handling.',
            ],
            [
                'name' => 'Wings Compute Node 01 — Sri Lanka (Colombo DC)',
                'slug' => 'wings-node-sl01',
                'type' => 'node',
                'target_url' => '127.0.0.1:8080',
                'category' => 'Game Compute Nodes',
                'expected_status_code' => 200,
                'check_interval_seconds' => 60,
                'timeout_seconds' => 5,
                'sort_order' => 5,
                'description' => 'Primary high-performance AMD EPYC game server node hosted in Colombo, Sri Lanka.',
            ],
            [
                'name' => 'Wings Compute Node 02 — Singapore (APAC Equinix)',
                'slug' => 'wings-node-sg01',
                'type' => 'tcp',
                'target_url' => 'one.one.one.one:443',
                'category' => 'Game Compute Nodes',
                'expected_status_code' => 200,
                'check_interval_seconds' => 60,
                'timeout_seconds' => 5,
                'sort_order' => 6,
                'description' => 'Low-latency APAC routing cluster in Equinix SG1 with ultra-low ping for Southeast Asia.',
            ],
            [
                'name' => 'STX Edge DNS & Anycast Resolution',
                'slug' => 'stx-dns-resolution',
                'type' => 'http',
                'target_url' => 'https://dns.google/resolve?name=info.shanutechx.com',
                'category' => 'Edge & Web Services',
                'expected_status_code' => 200,
                'check_interval_seconds' => 60,
                'timeout_seconds' => 5,
                'sort_order' => 7,
                'description' => 'Anycast DNS edge telemetry for game server and subdomain name resolutions.',
            ],
            [
                'name' => 'Sri Lanka Payment Rails (eZ Cash / HelaPay / Commercial Bank)',
                'slug' => 'stx-payment-rails',
                'type' => 'http',
                'target_url' => 'https://github.com',
                'category' => 'Payment Rails',
                'expected_status_code' => 200,
                'check_interval_seconds' => 60,
                'timeout_seconds' => 5,
                'sort_order' => 8,
                'description' => 'Automated payment gateways, webhook ingestion, and manual bank slip verification system.',
            ],
        ];

        foreach ($defaults as $item) {
            StatusMonitor::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
