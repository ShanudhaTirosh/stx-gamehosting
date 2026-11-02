@extends('hosting-platform::storefront.layout')

@section('title', 'System status')
@section('meta_description', 'Live availability and uptime of our storefront, control panel and supporting services, measured by our own automated checks.')

@section('content')
@php
    $color = fn ($st) => match ($st) { 'down' => 'var(--bad)', 'degraded' => 'var(--warn)', 'maintenance' => '#6AA9FF', null, '' => 'var(--line2)', default => 'var(--ok)' };
    $word  = fn ($st) => match ($st) { 'down' => 'Down', 'degraded' => 'Degraded', 'maintenance' => 'Maintenance', null, '' => 'Unchecked', default => 'Operational' };
    $barColor = fn ($st) => match ($st) { 'good' => 'var(--ok)', 'bad' => 'var(--bad)', default => 'var(--line2)' };
    $rangeLabels = ['60m' => '1 hour', '24h' => '24 hours', '30d' => '30 days', '90d' => '90 days'];
@endphp

<section class="border-b stx-rule">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="stx-label mb-4">System status</div>
            <h1 class="stx-display text-5xl sm:text-7xl text-white flex items-center gap-4">
                <span class="stx-dot" style="width:18px;height:18px;background: {{ $color($overallStatus) }}"></span>{{ $overallLabel }}
            </h1>
            <p class="mt-4 text-[var(--mut)]">Measured by our own automated checks. No data is filled in when a check has not run.</p>
        </div>
        @if($totalMonitors > 0)
        <div class="lg:text-right">
            <div class="stx-label">Average uptime</div>
            <div class="stx-mono text-4xl text-white">{{ number_format($globalUptime, 2) }}%</div>
            <div class="stx-label mt-1">across {{ $totalMonitors }} {{ \Illuminate\Support\Str::plural('service', $totalMonitors) }}</div>
        </div>
        @endif
    </div>
</section>

<section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 space-y-10">

        @foreach($activeIncidents as $incident)
            @php $isMaint = $incident->type === 'maintenance'; $latest = $incident->updates->first(); @endphp
            <a href="{{ route('hosting.status.incident_details', $incident->id) }}" class="block p-5 border stx-row" style="border-color: {{ $isMaint ? '#6AA9FF' : (in_array($incident->impact, ['major','critical']) ? 'var(--bad)' : 'var(--warn)') }}">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="stx-label">{{ $isMaint ? 'Maintenance' : 'Incident' }} / {{ $incident->status }}</span>
                    <span class="text-white font-semibold text-lg">{{ $incident->title }}</span>
                </div>
                @if($latest)<p class="mt-2 text-[15px] text-[var(--mut)]">{{ $latest->message }} <span class="stx-mono text-xs">({{ $latest->created_at->diffForHumans() }})</span></p>@endif
            </a>
        @endforeach

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex flex-wrap gap-2" role="group" aria-label="Time range">
                @foreach($rangeLabels as $key => $label)
                    <a href="?range={{ $key }}" class="px-3 py-1.5 text-sm border {{ $range === $key ? 'font-semibold' : 'text-[var(--mut)] hover:text-white' }}" style="border-color: {{ $range === $key ? 'var(--acc)' : 'var(--line2)' }}; {{ $range === $key ? 'color:var(--acc)' : '' }}" @if($range === $key) aria-current="true" @endif>{{ $label }}</a>
                @endforeach
            </div>
            <div class="flex items-center gap-4">
                <input id="monitor-search" type="search" placeholder="Filter services" class="stx-input sm:w-64" aria-label="Filter services">
                <a href="{{ route('hosting.status.incidents') }}" class="text-sm text-[var(--acc)] whitespace-nowrap">Past incidents <i class="ti ti-arrow-right"></i></a>
            </div>
        </div>

        @forelse($categories as $category => $items)
            <div class="category-block">
                <h2 class="stx-label mb-3">{{ $category }}</h2>
                <div class="stx-panel">
                    @foreach($items as $item)
                        <a href="{{ route('hosting.status.monitor', $item['slug']) }}" class="monitor-card stx-row block px-5 py-5 border-b stx-rule last:border-b-0" data-name="{{ strtolower($item['name']) }}" data-url="{{ strtolower((string) $item['target_url']) }}">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="stx-dot" style="background: {{ $color($item['current_status']) }}"></span>
                                    <span class="text-white font-semibold truncate">{{ $item['name'] }}</span>
                                    <span class="text-sm text-[var(--mut)] hidden sm:inline">{{ $word($item['current_status']) }}</span>
                                </div>
                                <div class="stx-mono text-sm text-[var(--mut)] flex items-center gap-5">
                                    <span>{{ $item['current_latency_ms'] ? round($item['current_latency_ms']) . ' ms' : '' }}</span>
                                    <span class="text-white">{{ $item['uptime_percentage'] !== null ? number_format($item['uptime_percentage'], 2) . '%' : '' }}</span>
                                </div>
                            </div>
                            @if(!empty($item['description']))<p class="mt-1 text-sm text-[var(--mut)]">{{ $item['description'] }}</p>@endif
                            <div class="mt-4 flex gap-[2px] h-7" role="img" aria-label="Uptime history for {{ $item['name'] }}">
                                @foreach($item['timeline'] as $seg)
                                    <span class="flex-1" style="background: {{ $barColor($seg['status']) }}" title="{{ $seg['start'] }} - {{ $seg['end'] }}: {{ $seg['tooltip'] }}"></span>
                                @endforeach
                            </div>
                            <div class="mt-1.5 flex justify-between stx-label"><span>{{ $rangeLabels[$range] ?? '' }} ago</span><span>now</span></div>
                            <div class="mt-1 stx-label">Last checked {{ $item['last_checked_at'] }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="stx-panel p-8 text-[var(--mut)]">No services are being monitored yet.</div>
        @endforelse

        <p class="stx-hint">Grey segments mean no check ran in that window. A new service starts with an empty history.</p>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('monitor-search');
    if (input) {
        input.addEventListener('input', function (e) {
            var q = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.monitor-card').forEach(function (c) {
                var hit = (c.dataset.name || '').includes(q) || (c.dataset.url || '').includes(q);
                c.style.display = hit ? '' : 'none';
            });
            document.querySelectorAll('.category-block').forEach(function (b) {
                var any = Array.prototype.some.call(b.querySelectorAll('.monitor-card'), function (c) { return c.style.display !== 'none'; });
                b.style.display = any ? '' : 'none';
            });
        });
    }
    // Refresh the page once a minute while the tab is visible and the user is not typing.
    setInterval(function () {
        if (document.visibilityState === 'visible' && (!input || input.value === '')) { window.location.reload(); }
    }, 60000);
});
</script>
@endsection
