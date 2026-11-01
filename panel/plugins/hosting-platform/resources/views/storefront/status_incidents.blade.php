@extends('hosting-platform::storefront.layout')

@section('title', 'Incidents & Maintenance Directory — STX Enterprises')

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-8 font-sans">

    <!-- Navigation Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('hosting.status.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white transition group">
            <i class="ti ti-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Back to Live Telemetry</span>
        </a>
        <a href="{{ url('/admin/status-incidents') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-white/5 hover:bg-white/10 text-gray-300 border border-white/10 transition">
            <i class="ti ti-plus"></i> Post Incident Update (Staff)
        </a>
    </div>

    <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Incidents & Maintenance History</h1>
        <p class="text-sm text-gray-400 mt-1">Official operational postmortems, ongoing maintenance notices, and platform updates.</p>
    </div>

    <!-- Active Events Section -->
    @if($activeIncidents->count() > 0 || $activeMaintenance->count() > 0)
        <div class="space-y-4">
            <h2 class="text-xs font-black uppercase tracking-wider text-rose-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Ongoing Operations & Incidents</span>
            </h2>

            <div class="space-y-3">
                @foreach($activeIncidents->concat($activeMaintenance) as $item)
                    <div class="rounded-2xl border border-white/10 bg-zinc-900/80 p-5 sm:p-6 transition backdrop-blur-md">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $item->type === 'maintenance' ? 'bg-amber-500/20 text-amber-400' : 'bg-rose-500/20 text-rose-400' }}">
                                    {{ $item->type }}
                                </span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-black/40 text-gray-300 uppercase">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </span>
                                <span class="text-xs text-gray-400">• {{ ucfirst($item->impact) }} Impact</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $item->title }}</h3>
                        @if($item->updates->count() > 0)
                            <div class="p-3 rounded-xl bg-black/40 border border-white/5 text-xs text-gray-300 space-y-1">
                                <div class="font-bold text-indigo-400 uppercase tracking-wide text-[10px]">Latest Update ({{ $item->updates->first()->created_at->format('H:i T') }})</div>
                                <p>{{ $item->updates->first()->message }}</p>
                            </div>
                        @endif
                        <div class="mt-4 pt-3 border-t border-white/5 text-right">
                            <a href="{{ route('hosting.status.incident_details', $item->id) }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 inline-flex items-center gap-1">
                                <span>View Detailed Incident Log</span>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Past Incidents & Maintenance -->
    <div class="space-y-6">
        <h2 class="text-xs font-black uppercase tracking-wider text-gray-400">Past Resolved Incidents (Last 30 Days)</h2>

        <div class="space-y-4">
            @forelse($pastIncidents->concat($pastMaintenance) as $item)
                <div class="rounded-2xl border border-white/10 bg-zinc-900/40 p-5 sm:p-6 transition hover:bg-zinc-900/60">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400">
                                Resolved
                            </span>
                            <span class="text-xs font-medium text-gray-400 uppercase">{{ $item->type }}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $item->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">
                        <a href="{{ route('hosting.status.incident_details', $item->id) }}" class="hover:text-indigo-400 transition">
                            {{ $item->title }}
                        </a>
                    </h3>
                    @if($item->updates->count() > 0)
                        <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">
                            {{ $item->updates->first()->message }}
                        </p>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-white/5 bg-zinc-900/20 p-8 text-center text-gray-500 text-xs">
                    <i class="ti ti-circle-check text-2xl text-emerald-500/60 mb-2 block"></i>
                    No major incidents or service disruptions reported in the last 30 days. Perfect 100% operational window.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
