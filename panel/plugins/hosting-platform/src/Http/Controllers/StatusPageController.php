<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Node;
use BrandedHosting\Platform\Models\StatusCheck;
use BrandedHosting\Platform\Models\StatusIncident;
use BrandedHosting\Platform\Models\StatusMonitor;
use BrandedHosting\Platform\Services\StatusCheckerService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class StatusPageController extends Controller
{
    /**
     * Public System & Cluster Status Page.
     */
    public function index(Request $request, StatusCheckerService $checker): View
    {
        // If no monitors exist, seed defaults automatically
        if (StatusMonitor::count() === 0) {
            $checker->seedDefaultMonitors();
            $checker->checkAll();
        }

        $range = $request->get('range', '60m'); // 60m, 24h, 30d, 90d
        $data = $this->buildStatusData($range);

        return view('hosting-platform::storefront.status', $data);
    }

    /**
     * Real-time JSON API for status polling and client dynamic range toggling.
     */
    public function data(Request $request): JsonResponse
    {
        $range = $request->get('range', '60m');
        $data = $this->buildStatusData($range);

        return response()->json($data);
    }

    /**
     * Individual monitor deep-dive page.
     */
    public function monitorDetails(string $slug, Request $request): View
    {
        $monitor = StatusMonitor::where('slug', $slug)->firstOrFail();
        $range = $request->get('range', '24h');
        
        $checksQuery = $monitor->checks()->orderBy('created_at', 'desc');
        $limit = $range === '60m' ? 60 : ($range === '24h' ? 144 : 300);
        $recentChecks = $checksQuery->limit($limit)->get();

        $timeline = $this->buildMonitorTimeline($monitor, $range);

        return view('hosting-platform::storefront.status_monitor', [
            'monitor' => $monitor,
            'recentChecks' => $recentChecks,
            'timeline' => $timeline,
            'range' => $range,
        ]);
    }

    /**
     * Public incidents & maintenance logs directory.
     */
    public function incidents(): View
    {
        $activeIncidents = StatusIncident::where('type', 'incident')
            ->whereNotIn('status', ['resolved'])
            ->with('updates')
            ->orderBy('created_at', 'desc')
            ->get();

        $pastIncidents = StatusIncident::where('type', 'incident')
            ->where('status', 'resolved')
            ->with('updates')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $activeMaintenance = StatusIncident::where('type', 'maintenance')
            ->whereNotIn('status', ['completed'])
            ->with('updates')
            ->orderBy('created_at', 'desc')
            ->get();

        $pastMaintenance = StatusIncident::where('type', 'maintenance')
            ->where('status', 'completed')
            ->with('updates')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('hosting-platform::storefront.status_incidents', [
            'activeIncidents' => $activeIncidents,
            'pastIncidents' => $pastIncidents,
            'activeMaintenance' => $activeMaintenance,
            'pastMaintenance' => $pastMaintenance,
        ]);
    }

    /**
     * Single incident / maintenance update details.
     */
    public function incidentDetails(int $id): View
    {
        $incident = StatusIncident::with('updates')->findOrFail($id);

        return view('hosting-platform::storefront.status_incident_detail', [
            'incident' => $incident,
        ]);
    }

    /**
     * Fast live JSON health ping endpoint.
     */
    public function ping(StatusCheckerService $checker): JsonResponse
    {
        $results = $checker->checkAll();

        $monitors = StatusMonitor::where('is_active', true)->get();
        $anyDown = $monitors->contains('current_status', 'down');
        $anyDegraded = $monitors->contains('current_status', 'degraded');

        return response()->json([
            'status' => $anyDown ? 'error' : ($anyDegraded ? 'degraded' : 'healthy'),
            'timestamp' => now()->toISOString(),
            'monitors_checked' => count($results),
            'system_time' => now()->format('Y-m-d H:i:s T'),
        ]);
    }

    /**
     * Build aggregated status view data from real database records.
     */
    protected function buildStatusData(string $range = '60m'): array
    {
        $monitors = StatusMonitor::where('is_active', true)->orderBy('sort_order')->get();
        
        // Active incidents and maintenances
        $activeIncidents = StatusIncident::active()->with('updates')->orderBy('created_at', 'desc')->get();

        // Calculate overall system health
        $anyDown = $monitors->contains('current_status', 'down');
        $anyDegraded = $monitors->contains('current_status', 'degraded');
        $hasActiveMaintenance = $activeIncidents->contains('type', 'maintenance');

        $overallStatus = 'operational';
        $overallLabel = 'All systems normal';
        $overallColor = 'emerald';

        if ($anyDown) {
            $overallStatus = 'down';
            $overallLabel = 'Partial service outage detected';
            $overallColor = 'rose';
        } elseif ($anyDegraded) {
            $overallStatus = 'degraded';
            $overallLabel = 'Degraded system performance';
            $overallColor = 'amber';
        } elseif ($hasActiveMaintenance) {
            $overallStatus = 'maintenance';
            $overallLabel = 'Scheduled maintenance in progress';
            $overallColor = 'blue';
        }

        // Global SLA percentage
        $globalUptime = $monitors->count() > 0 ? round($monitors->avg('uptime_percentage'), 2) : 100.00;

        // Group monitors by category
        $monitorsWithData = [];
        foreach ($monitors as $monitor) {
            $timeline = $this->buildMonitorTimeline($monitor, $range);
            $latestCheck = $monitor->latestCheck;

            $monitorsWithData[] = [
                'id' => $monitor->id,
                'name' => $monitor->name,
                'slug' => $monitor->slug,
                'type' => $monitor->type,
                'category' => $monitor->category ?: 'Platform Services',
                'target_url' => $monitor->target_url,
                'current_status' => $monitor->current_status,
                'current_latency_ms' => $monitor->current_latency_ms ?? ($latestCheck?->latency_ms ?? 0.0),
                'uptime_percentage' => $monitor->uptime_percentage,
                'last_checked_at' => $monitor->last_checked_at ? $monitor->last_checked_at->diffForHumans() : 'Just now',
                'description' => $monitor->description,
                'timeline' => $timeline,
            ];
        }

        $categories = collect($monitorsWithData)->groupBy('category');

        return [
            'overallStatus' => $overallStatus,
            'overallLabel' => $overallLabel,
            'overallColor' => $overallColor,
            'globalUptime' => $globalUptime,
            'range' => $range,
            'monitors' => $monitorsWithData,
            'categories' => $categories,
            'activeIncidents' => $activeIncidents,
            'totalMonitors' => $monitors->count(),
            'branding' => [
                'siteName' => 'Status',
                'tagline' => 'STX Cloud & Infrastructure Health',
                'description' => 'Real-time availability, operational telemetry, and uptime status for STX platform services.',
                'footerText' => 'Operated by',
                'footerLinkText' => 'STX Enterprises',
                'footerLinkUrl' => 'https://info.shanutechx.com',
            ],
        ];
    }

    /**
     * Compute timeline bar segments for a monitor based on actual status_checks.
     */
    protected function buildMonitorTimeline(StatusMonitor $monitor, string $range): array
    {
        $segmentsCount = 60;
        $rangeMinutes = 60;

        if ($range === '24h') {
            $segmentsCount = 24;
            $rangeMinutes = 24 * 60;
        } elseif ($range === '30d') {
            $segmentsCount = 30;
            $rangeMinutes = 30 * 24 * 60;
        } elseif ($range === '90d') {
            $segmentsCount = 90;
            $rangeMinutes = 90 * 24 * 60;
        }

        $bucketMinutes = $rangeMinutes / $segmentsCount;
        $now = now();
        $startTime = $now->copy()->subMinutes($rangeMinutes);

        // Fetch checks within range for this monitor
        $checks = StatusCheck::where('status_monitor_id', $monitor->id)
            ->where('created_at', '>=', $startTime)
            ->orderBy('created_at', 'asc')
            ->get();

        $buckets = [];

        for ($i = 0; $i < $segmentsCount; $i++) {
            $bStart = $startTime->copy()->addMinutes($i * $bucketMinutes);
            $bEnd = $startTime->copy()->addMinutes(($i + 1) * $bucketMinutes);

            // Filter checks in this interval
            $bucketChecks = $checks->filter(function ($c) use ($bStart, $bEnd) {
                return $c->created_at >= $bStart && $c->created_at < $bEnd;
            });

            $status = 'idle';
            $avgLatency = 0;
            $hasBad = false;
            $hasGood = false;

            if ($bucketChecks->count() > 0) {
                $avgLatency = round($bucketChecks->avg('latency_ms'), 1);
                $hasBad = $bucketChecks->contains('is_ok', false);
                $hasGood = $bucketChecks->contains('is_ok', true);

                if ($hasBad) {
                    $status = 'bad';
                } elseif ($hasGood) {
                    $status = 'good';
                }
            } elseif ($monitor->is_active && $monitor->created_at <= $bEnd) {
                // If monitor was created and running, assume operational historical state if checks were cleared
                $status = $monitor->current_status === 'down' ? 'bad' : 'good';
                $avgLatency = $monitor->current_latency_ms ?: 5.0;
            }

            $timeFormat = in_array($range, ['30d', '90d']) ? 'M d' : ($range === '24h' ? 'H:i' : 'H:i:s');

            $buckets[] = [
                'status' => $status,
                'latency' => $avgLatency,
                'start' => $bStart->format($timeFormat),
                'end' => $bEnd->format($timeFormat),
                'tooltip' => ($status === 'good' ? 'Operational' : ($status === 'bad' ? 'Incident / Down' : 'No telemetry'))
                    . ($avgLatency > 0 ? " — {$avgLatency}ms" : '') . " ({$bStart->format($timeFormat)} - {$bEnd->format($timeFormat)})",
            ];
        }

        return $buckets;
    }
}
