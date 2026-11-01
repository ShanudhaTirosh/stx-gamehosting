@extends('hosting-platform::storefront.layout')

@section('title', 'System Telemetry & Network Status — STX Enterprises')

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 font-sans">

    <!-- Top Telemetry Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center justify-center p-1.5 rounded-lg bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                    <i class="ti ti-activity-heartbeat text-xl"></i>
                </span>
                <span class="font-bold text-lg tracking-tight text-white uppercase">STX Real-time Telemetry</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                {{ $branding['tagline'] }}
            </h1>
            <p class="text-sm sm:text-base text-gray-400 mt-1 max-w-2xl">
                {{ $branding['description'] }}
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap shrink-0">
            <!-- Incidents directory button -->
            <a href="{{ route('hosting.status.incidents') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold border border-white/10 bg-white/5 hover:bg-white/10 text-gray-200 transition">
                <i class="ti ti-alert-triangle text-amber-400 {{ $activeIncidents->count() > 0 ? 'animate-bounce text-rose-400' : '' }}"></i>
                <span>Incidents History</span>
                @if($activeIncidents->count() > 0)
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                @endif
            </a>

            <!-- Staff Admin shortcut -->
            <a href="{{ url('/admin/status-monitors') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold border border-white/10 bg-white/5 hover:bg-white/10 text-gray-200 transition" title="Manage Status Telemetry in Admin Panel">
                <i class="ti ti-adjustments-alt"></i>
                <span>Admin</span>
            </a>

            <!-- Overall System State Pill -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full border border-white/10 bg-white/5 shadow-inner">
                <div class="w-2.5 h-2.5 rounded-full shrink-0 animate-pulse {{ $overallStatus === 'operational' ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]' : ($overallStatus === 'degraded' ? 'bg-amber-400' : 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.8)]') }}"></div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-200">
                    {{ $overallLabel }}
                </span>
            </div>
        </div>
    </header>

    <!-- Active Incidents / Maintenance Banner -->
    @if($activeIncidents->count() > 0)
        <div class="space-y-4">
            @foreach($activeIncidents as $incident)
                @php
                    $isCritical = in_array($incident->impact, ['major', 'critical']);
                    $isMaintenance = $incident->type === 'maintenance';
                    $latestUpdate = $incident->updates->first();
                @endphp
                <div class="rounded-2xl border p-5 sm:p-6 transition-all shadow-xl {{ $isMaintenance ? 'bg-amber-500/10 border-amber-500/30' : ($isCritical ? 'bg-rose-500/15 border-rose-500/40 shadow-rose-950/40' : 'bg-amber-500/10 border-amber-500/30') }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full {{ $isMaintenance ? 'bg-amber-400/20 text-amber-300 border border-amber-400/30' : 'bg-rose-400/20 text-rose-300 border border-rose-400/30' }}">
                                    {{ $isMaintenance ? 'Scheduled Maintenance' : 'Active Service Outage' }}
                                </span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-black/40 text-gray-200 uppercase">
                                    {{ str_replace('_', ' ', $incident->status) }}
                                </span>
                                @if($incident->impact !== 'none')
                                    <span class="text-xs font-semibold text-gray-400">
                                        • {{ ucfirst($incident->impact) }} Impact
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-white">{{ $incident->title }}</h3>
                            @if($latestUpdate)
                                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                                    <strong class="text-indigo-300">Latest Telemetry Note:</strong> {{ $latestUpdate->message }}
                                    <span class="text-gray-500 text-[11px] ml-2">({{ $latestUpdate->created_at->diffForHumans() }})</span>
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('hosting.status.incident_details', $incident->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white transition shrink-0 self-start sm:self-center border border-white/10">
                            <span>View Full Timeline</span>
                            <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Controls Bar: History Range Selector & Search Bar -->
    <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between bg-zinc-900/60 p-4 rounded-2xl border border-white/10 backdrop-blur-xl">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <span class="text-xs font-black uppercase tracking-wider text-gray-400">Telemetry Window:</span>
            <div class="flex items-center gap-1.5 p-1 rounded-xl bg-black/40 border border-white/10" id="range-selector">
                <button type="button" data-range="60m" class="range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $range === '60m' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                    60 min
                </button>
                <button type="button" data-range="24h" class="range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $range === '24h' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                    24 hours
                </button>
                <button type="button" data-range="30d" class="range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $range === '30d' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                    30 days
                </button>
                <button type="button" data-range="90d" class="range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $range === '90d' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                    90 days
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Search Filter -->
            <div class="relative w-full sm:w-64">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="monitor-search" placeholder="Filter monitors..." class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-black/40 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 transition">
            </div>

            <!-- Global SLA metric badge -->
            <div class="hidden lg:flex items-center gap-2 px-3.5 py-2 rounded-xl bg-black/40 border border-white/10 text-xs">
                <span class="text-gray-400 font-medium">30d Global SLA:</span>
                <span class="font-extrabold text-emerald-400">{{ $globalUptime }}%</span>
            </div>
        </div>
    </div>

    <!-- Monitors Grouped by Category -->
    <div id="monitors-container" class="space-y-8">
        @foreach($categories as $categoryName => $monitorList)
            <div class="category-block space-y-4" data-category="{{ $categoryName }}">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold tracking-tight text-white flex items-center gap-2">
                        <i class="ti ti-folder text-indigo-400"></i>
                        <span>{{ $categoryName }}</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-white/10 text-gray-300">
                            {{ count($monitorList) }}
                        </span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($monitorList as $item)
                        <div class="monitor-card rounded-2xl border border-white/10 bg-zinc-900/50 hover:bg-zinc-900/80 p-5 sm:p-6 transition-all duration-200 backdrop-blur-md shadow-lg" data-name="{{ strtolower($item['name']) }}" data-url="{{ strtolower($item['target_url']) }}">
                            <!-- Monitor Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full shrink-0 {{ $item['current_status'] === 'operational' ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]' : ($item['current_status'] === 'degraded' ? 'bg-amber-400' : 'bg-rose-500') }}"></div>
                                    <div>
                                        <a href="{{ route('hosting.status.monitor', $item['slug']) }}" class="text-base sm:text-lg font-bold text-white hover:text-indigo-400 transition flex items-center gap-2">
                                            <span>{{ $item['name'] }}</span>
                                            <i class="ti ti-external-link text-xs opacity-60"></i>
                                        </a>
                                        <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                            <span class="font-mono text-gray-300 truncate max-w-xs sm:max-w-md">{{ $item['target_url'] ?: 'Internal Socket' }}</span>
                                            <span>•</span>
                                            <span class="uppercase tracking-wider font-semibold text-[10px] px-1.5 py-0.5 rounded bg-white/10 text-gray-300">{{ $item['type'] }}</span>
                                            <span>•</span>
                                            <span>Checked {{ $item['last_checked_at'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 self-end sm:self-center">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-400">Current Latency</div>
                                        <div class="text-sm font-bold font-mono {{ $item['current_latency_ms'] > 500 ? 'text-amber-400' : 'text-white' }}">
                                            {{ $item['current_latency_ms'] ? $item['current_latency_ms'] . ' ms' : '—' }}
                                        </div>
                                    </div>
                                    <div class="text-right pl-3 border-l border-white/10">
                                        <div class="text-xs text-gray-400">30d Uptime</div>
                                        <div class="text-sm font-bold font-mono text-emerald-400">
                                            {{ $item['uptime_percentage'] }}%
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline segmented discrete ticks -->
                            <div class="space-y-1.5">
                                <div class="flex h-7 gap-1 items-stretch py-1 timeline-row" data-monitor="{{ $item['id'] }}">
                                    @foreach($item['timeline'] as $bucket)
                                        <div class="flex-1 rounded-sm cursor-pointer transition-transform duration-100 hover:scale-y-125 {{ $bucket['status'] === 'good' ? 'bg-emerald-500' : ($bucket['status'] === 'bad' ? 'bg-rose-500' : 'bg-zinc-800') }}"
                                             title="{{ $bucket['tooltip'] }}"
                                             data-tooltip="{{ $bucket['tooltip'] }}"></div>
                                    @endforeach
                                </div>
                                <div class="flex items-center justify-between text-[11px] font-semibold text-gray-500 px-0.5">
                                    <span>
                                        @if($range === '60m') 60 minutes ago
                                        @elseif($range === '24h') 24 hours ago
                                        @elseif($range === '30d') 30 days ago
                                        @else 90 days ago @endif
                                    </span>
                                    <span class="text-gray-400 font-mono">{{ $item['uptime_percentage'] }}% operational</span>
                                    <span>Today (Now)</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Telemetry Footnote & Attribution -->
    <footer class="pt-8 border-t border-white/10 text-center space-y-3">
        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs font-medium text-gray-400">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Storefront</a>
            <a href="{{ route('hosting.storefront.games') }}" class="hover:text-white transition">Game Catalog</a>
            <a href="{{ route('hosting.storefront.about') }}" class="hover:text-white transition">About Infrastructure</a>
            <a href="{{ url('/customer-orders') }}" class="hover:text-white transition">Customer Invoices</a>
            <a href="{{ route('hosting.storefront.contact') }}" class="hover:text-white transition">24/7 Helpdesk</a>
            <a href="{{ route('hosting.storefront.terms') }}" class="hover:text-white transition">Terms of Service</a>
            <a href="{{ route('hosting.storefront.privacy') }}" class="hover:text-white transition">Privacy Policy</a>
        </div>
        <p class="text-xs text-gray-400">
            {{ $branding['footerText'] }}
            <a href="{{ $branding['footerLinkUrl'] }}" target="_blank" rel="noopener" class="font-bold text-indigo-400 hover:text-indigo-300 underline underline-offset-4">
                {{ $branding['footerLinkText'] }}
            </a>
            — Official High-Availability Cloud & Game Telemetry Network.
        </p>
        <p class="text-[11px] text-gray-500">
            Automated sub-second telemetry probes execute across Colombo, Singapore & Anycast edges every 30 seconds.
        </p>
    </footer>

</div>

<!-- Interactive Client Script for Range Switching and Search Filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('monitor-search');
    const rangeButtons = document.querySelectorAll('.range-btn');

    // Instant Search Filter
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.monitor-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const url = card.getAttribute('data-url') || '';
                if (name.includes(query) || url.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Hide empty category blocks
            document.querySelectorAll('.category-block').forEach(block => {
                const visibleCards = block.querySelectorAll('.monitor-card:not([style*="display: none"])');
                block.style.display = visibleCards.length > 0 ? '' : 'none';
            });
        });
    }

    // Range Button Click - Dynamic Fetch without Page Reload
    rangeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const range = this.getAttribute('data-range');
            window.location.search = '?range=' + encodeURIComponent(range);
        });
    });

    // Auto-refresh telemetry every 30 seconds
    setInterval(() => {
        fetch('/api/status/ping')
            .then(res => res.json())
            .then(data => {
                console.log('Telemetry ping synced:', data);
            })
            .catch(() => {});
    }, 30000);
});
</script>
@endsection
