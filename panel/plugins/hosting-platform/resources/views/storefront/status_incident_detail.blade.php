@extends('hosting-platform::storefront.layout')

@section('title', $incident->title . ' — Incident Details')

@section('content')
<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-8 font-sans">

    <!-- Navigation Header -->
    <div>
        <a href="{{ route('hosting.status.incidents') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white transition group">
            <i class="ti ti-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Back to All Incidents</span>
        </a>
    </div>

    <!-- Main Incident Info -->
    <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 sm:p-8 backdrop-blur-xl shadow-2xl space-y-6">
        <div class="space-y-3 pb-6 border-b border-white/10">
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $incident->type === 'maintenance' ? 'bg-amber-400/20 text-amber-300' : 'bg-rose-400/20 text-rose-300' }}">
                    {{ ucfirst($incident->type) }}
                </span>
                <span class="text-xs font-bold px-2 py-0.5 rounded bg-black/40 text-gray-200 uppercase">
                    {{ str_replace('_', ' ', $incident->status) }}
                </span>
                <span class="text-xs text-gray-400">• {{ ucfirst($incident->impact) }} Impact</span>
                <span class="text-xs text-gray-500">• Reported {{ $incident->created_at->format('M d, Y H:i T') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                {{ $incident->title }}
            </h1>
        </div>

        <!-- Chronological Step-by-Step Update Timeline -->
        <div class="space-y-4">
            <h2 class="text-xs font-black uppercase tracking-wider text-gray-400">Incident Updates & Audit Log</h2>

            <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-white/10">
                @forelse($incident->updates as $update)
                    <div class="relative group">
                        <!-- Timeline Node Dot -->
                        <div class="absolute -left-6 top-1.5 w-3.5 h-3.5 rounded-full border-2 border-zinc-900 {{ in_array($update->status, ['resolved', 'completed']) ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]' : ($update->status === 'investigating' ? 'bg-rose-500' : 'bg-amber-400') }}"></div>

                        <div class="rounded-2xl border border-white/10 bg-black/40 p-4 sm:p-5 space-y-2">
                            <div class="flex items-center justify-between gap-2 text-xs">
                                <span class="font-bold uppercase tracking-wider text-indigo-400">
                                    {{ ucwords(str_replace('_', ' ', $update->status)) }}
                                </span>
                                <span class="text-gray-500 font-mono">{{ $update->created_at->format('M d, H:i T') }} ({{ $update->created_at->diffForHumans() }})</span>
                            </div>
                            <p class="text-sm text-gray-200 leading-relaxed">
                                {{ $update->message }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-white/5 bg-black/20 p-4 text-xs text-gray-500">
                        Initial incident logged. Telemetry team is actively diagnosing metrics and root causes.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
