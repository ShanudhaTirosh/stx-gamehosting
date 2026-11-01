@extends('hosting-platform::storefront.layout')

@section('title', $monitor->name . ' — STX Status Telemetry')

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto space-y-8 font-sans">

    <!-- Back to Status Navigation -->
    <div>
        <a href="{{ route('hosting.status.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white transition group">
            <i class="ti ti-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Back to All Monitors</span>
        </a>
    </div>

    <!-- Monitor Summary Card -->
    <div class="rounded-3xl border border-white/10 bg-zinc-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-white/10">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full {{ $monitor->current_status === 'operational' ? 'bg-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.8)]' : ($monitor->current_status === 'degraded' ? 'bg-amber-400' : 'bg-rose-500') }}"></div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white">{{ $monitor->name }}</h1>
                </div>
                <div class="flex items-center gap-2.5 text-xs text-gray-400 flex-wrap">
                    <span class="font-mono text-gray-300">{{ $monitor->target_url ?: 'Internal Probe' }}</span>
                    <span>•</span>
                    <span class="uppercase tracking-wider font-semibold text-[10px] px-2 py-0.5 rounded bg-white/10 text-gray-300">{{ $monitor->type }}</span>
                    <span>•</span>
                    <span class="text-indigo-400 font-semibold">{{ $monitor->category }}</span>
                </div>
                @if($monitor->description)
                    <p class="text-sm text-gray-400 max-w-2xl mt-1 leading-relaxed">{{ $monitor->description }}</p>
                @endif
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right">
                    <div class="text-xs text-gray-400">Current Status</div>
                    <div class="text-base font-bold uppercase tracking-wider {{ $monitor->current_status === 'operational' ? 'text-emerald-400' : ($monitor->current_status === 'degraded' ? 'text-amber-400' : 'text-rose-500') }}">
                        {{ ucfirst($monitor->current_status) }}
                    </div>
                </div>
                <div class="text-right pl-6 border-l border-white/10">
                    <div class="text-xs text-gray-400">Live Latency</div>
                    <div class="text-lg font-bold font-mono text-white">
                        {{ $monitor->current_latency_ms ? $monitor->current_latency_ms . ' ms' : '—' }}
                    </div>
                </div>
                <div class="text-right pl-6 border-l border-white/10">
                    <div class="text-xs text-gray-400">30d Uptime</div>
                    <div class="text-lg font-bold font-mono text-emerald-400">
                        {{ $monitor->uptime_percentage }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Segment -->
        <div class="pt-6 space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-wider">
                <span>Telemetry Uptime Timeline</span>
                <span class="text-indigo-400 font-mono">{{ count($timeline) }} Recorded Intervals</span>
            </div>

            <div class="flex h-8 gap-1 items-stretch py-1 bg-black/30 p-1.5 rounded-xl border border-white/5">
                @foreach($timeline as $bucket)
                    <div class="flex-1 rounded-sm cursor-pointer transition-transform duration-100 hover:scale-y-125 {{ $bucket['status'] === 'good' ? 'bg-emerald-500' : ($bucket['status'] === 'bad' ? 'bg-rose-500' : 'bg-zinc-800') }}"
                         title="{{ $bucket['tooltip'] }}"></div>
                @endforeach
            </div>

            <div class="flex items-center justify-between text-[11px] font-semibold text-gray-500 px-1">
                <span>Interval start</span>
                <span class="text-emerald-400">Target SLA: 99.99%</span>
                <span>Now</span>
            </div>
        </div>
    </div>

    <!-- Recent Check History Table -->
    <div class="rounded-3xl border border-white/10 bg-zinc-900/60 p-6 sm:p-8 backdrop-blur-xl shadow-xl space-y-4">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="ti ti-history text-indigo-400"></i>
            <span>Recent Health Probes (Live Audit Log)</span>
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-gray-400 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Probe Timestamp</th>
                        <th class="py-3 px-4">Result</th>
                        <th class="py-3 px-4">Latency</th>
                        <th class="py-3 px-4">HTTP / Socket Code</th>
                        <th class="py-3 px-4">Diagnostic Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 font-mono">
                    @forelse($recentChecks as $check)
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-3 px-4 text-gray-300 font-sans">
                                {{ $check->created_at->format('M d, Y — H:i:s') }}
                                <span class="text-gray-500 text-[11px] ml-1">({{ $check->created_at->diffForHumans() }})</span>
                            </td>
                            <td class="py-3 px-4">
                                @if($check->is_ok)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        <i class="ti ti-check"></i> Healthy
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        <i class="ti ti-x"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold {{ $check->latency_ms > 500 ? 'text-amber-400' : 'text-white' }}">
                                {{ $check->latency_ms }} ms
                            </td>
                            <td class="py-3 px-4 text-gray-400">
                                {{ $check->status_code ?: 'Socket OK' }}
                            </td>
                            <td class="py-3 px-4 text-gray-400 font-sans">
                                {{ $check->error_message ?: 'Payload validated & operational' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 font-sans">
                                No probe records logged yet. Run a telemetry scan to populate live audit rows.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
