<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-emerald-400 shrink-0" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);">
                        <x-filament::icon icon="tabler-activity-heartbeat" class="w-4 h-4" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-sm text-white tracking-tight truncate">Live Cluster Telemetry & Global Nodes</h3>
                        <p class="text-[11px] text-gray-400 truncate">Microsecond network probes & infrastructure state</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                    <button
                        type="button"
                        wire:click="pingAll"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-amber-300 transition hover:bg-amber-500/20 shrink-0"
                        style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25);"
                    >
                        <x-filament::icon icon="tabler-refresh" class="w-3.5 h-3.5" />
                        <span>Probe All</span>
                    </button>

                    <a
                        href="/status"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-300 transition hover:text-white hover:bg-white/10 shrink-0"
                        style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08);"
                    >
                        <span>Public Status</span>
                        <x-filament::icon icon="tabler-external-link" class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>
        </x-slot>

        <!-- Active Maintenance / Outage Alert Banner (Clean Single-Block Dispatch) -->
        @if($activeIncidents->count() > 0)
            <div class="mb-3.5 space-y-2">
                @foreach($activeIncidents as $incident)
                    <div class="p-3.5 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs transition duration-150" style="background: {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.08)' : 'rgba(244, 63, 94, 0.08)' }}; border: 1px solid {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.25)' : 'rgba(244, 63, 94, 0.25)' }}; min-width: 0;">
                        <div class="flex items-start gap-3 min-w-0 flex-1">
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 mt-0.5 {{ $incident->type === 'maintenance' ? 'text-amber-400' : 'text-rose-400' }}" style="background: {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.18)' : 'rgba(244, 63, 94, 0.18)' }}; border: 1px solid {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.3)' : 'rgba(244, 63, 94, 0.3)' }};">
                                <x-filament::icon icon="tabler-alert-triangle" class="w-4 h-4" />
                            </span>
                            <div class="min-w-0 flex-1 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shrink-0" style="background: {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(244, 63, 94, 0.2)' }}; color: {{ $incident->type === 'maintenance' ? '#fbbf24' : '#fb7185' }}; border: 1px solid {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.3)' : 'rgba(244, 63, 94, 0.3)' }};">
                                        {{ $incident->type }}
                                    </span>
                                    <strong class="text-white text-xs font-bold leading-tight" title="{{ $incident->title }}">{{ $incident->title }}</strong>
                                </div>
                                @if($incident->updates->count() > 0)
                                    <p class="text-gray-300 text-[11px] leading-relaxed" title="{{ $incident->updates->first()->message }}">{{ $incident->updates->first()->message }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right-Aligned Compact Action Button -->
                        <div class="shrink-0 self-start md:self-center">
                            <a
                                href="/admin/status-incidents"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition group"
                                style="background: {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.15)' : 'rgba(244, 63, 94, 0.15)' }}; border: 1px solid {{ $incident->type === 'maintenance' ? 'rgba(245, 158, 11, 0.35)' : 'rgba(244, 63, 94, 0.35)' }}; color: {{ $incident->type === 'maintenance' ? '#fbbf24' : '#fb7185' }};"
                            >
                                <span>Manage Incident</span>
                                <x-filament::icon icon="tabler-chevron-right" class="w-3.5 h-3.5 transition group-hover:translate-x-0.5" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Grid of Monitored Nodes & Services (2 Columns with clean borders and spacing) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" style="width: 100%;">
            @forelse($monitors as $monitor)
                @php
                    $isOk = $monitor->current_status === 'operational';
                    $isDegraded = $monitor->current_status === 'degraded';
                    $dotColor = $isOk ? '#10b981' : ($isDegraded ? '#f59e0b' : '#f43f5e');
                    $dotGlow = $isOk ? 'rgba(16, 185, 129, 0.6)' : ($isDegraded ? 'rgba(245, 158, 11, 0.6)' : 'rgba(244, 63, 94, 0.6)');
                    $badgeBg = match(strtoupper($monitor->type)) {
                        'HTTP', 'HTTPS' => 'rgba(59, 130, 246, 0.12)',
                        'DATABASE' => 'rgba(168, 85, 247, 0.12)',
                        'REDIS' => 'rgba(239, 68, 68, 0.12)',
                        'NODE' => 'rgba(16, 185, 129, 0.12)',
                        default => 'rgba(255, 255, 255, 0.06)',
                    };
                    $badgeColor = match(strtoupper($monitor->type)) {
                        'HTTP', 'HTTPS' => '#60a5fa',
                        'DATABASE' => '#c084fc',
                        'REDIS' => '#f87171',
                        'NODE' => '#34d399',
                        default => '#9ca3af',
                    };
                    $formattedLatency = $monitor->current_latency_ms
                        ? ($monitor->current_latency_ms < 100
                            ? number_format($monitor->current_latency_ms, 2) . ' ms'
                            : number_format($monitor->current_latency_ms, 0) . ' ms')
                        : null;
                @endphp
                <div class="p-3 rounded-xl flex flex-col justify-between gap-2.5 transition duration-150 hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0; overflow: hidden; width: 100%;">
                    <div class="space-y-1" style="min-width: 0;">
                        <!-- Top State Dot & Type Badge -->
                        <div class="flex items-center justify-between gap-2" style="min-width: 0;">
                            <div class="flex items-center gap-2 min-w-0 flex-1" style="overflow: hidden;">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $dotColor }}; box-shadow: 0 0 6px {{ $dotGlow }};"></span>
                                <span class="font-bold text-xs text-white" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1; min-width: 0;" title="{{ $monitor->name }}">
                                    {{ $monitor->name }}
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider shrink-0" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border: 1px solid rgba(255, 255, 255, 0.06);">
                                {{ $monitor->type }}
                            </span>
                        </div>

                        <!-- Target Subtitle -->
                        <div class="text-[11px] font-mono text-gray-400 pl-4.5" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $monitor->target_url }}">
                            {{ $monitor->target_url ?: 'Internal Socket' }}
                        </div>
                    </div>

                    <!-- Bottom Metrics Bar -->
                    <div class="pt-2 flex items-center justify-between text-xs border-t" style="border-color: rgba(255, 255, 255, 0.05); min-width: 0;">
                        <div class="flex items-center gap-1.5 font-mono shrink-0">
                            <span class="text-gray-400 text-[10px]">Latency:</span>
                            @if($formattedLatency)
                                <span class="font-semibold {{ $monitor->current_latency_ms > 1500 ? 'text-amber-400' : 'text-emerald-400' }}">
                                    {{ $formattedLatency }}
                                </span>
                            @else
                                <span class="text-rose-400 font-semibold">Unreachable</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5 font-mono shrink-0">
                            <span class="text-gray-400 text-[10px]">30d:</span>
                            <span class="font-semibold text-white px-1.5 py-0.5 rounded text-[10px]" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.06);">
                                {{ $monitor->uptime_percentage }}%
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-6 text-center text-xs text-gray-500">
                    No active monitors found. Visit <a href="/admin/status-monitors" class="text-indigo-400 underline">Status Monitors</a> to configure telemetry.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>