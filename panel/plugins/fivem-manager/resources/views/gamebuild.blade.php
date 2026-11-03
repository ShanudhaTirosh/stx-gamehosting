<x-filament-panels::page>
    <div class="space-y-6">
        @if($activeBuild)
            <div class="flex items-center justify-between px-5 py-3 rounded-lg bg-[#0E0F12] border border-gray-800 text-xs">
                <div class="flex items-center gap-2.5 text-gray-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Currently Enforced Build: <strong class="text-white font-mono">b{{ $activeBuild }}</strong></span>
                    <span class="text-gray-500 hidden sm:inline">(configured via <code class="text-red-400">set sv_enforceGameBuild {{ $activeBuild }}</code> in server.cfg)</span>
                </div>
                <span class="text-gray-400 text-[11px]">Restart server to apply changes</span>
            </div>
        @endif

        {{-- GameBuild Cards Grid matching user reference --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($builds as $item)
                @php
                    $isActive = ($activeBuild === $item['build']);
                @endphp
                <div class="p-5 rounded-lg bg-[#0F1013] border {{ $isActive ? 'border-red-600 shadow-lg shadow-red-950/40 ring-1 ring-red-600/50' : 'border-[#1E2025]' }} flex flex-col justify-between min-h-[145px] transition hover:border-[#2E323A]">
                    <div>
                        <div class="text-[13px] font-semibold text-[#8C92A0] tracking-wide">
                            {{ $item['name'] }}
                        </div>
                        <p class="text-[13px] text-[#E0E2E8] mt-3 leading-relaxed">
                            {{ $item['description'] }}
                        </p>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        @if($isActive)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400">
                                <i class="ti ti-check"></i> Activated
                            </span>
                            <button type="button" disabled class="px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider text-white bg-emerald-700/60 cursor-default">
                                Active
                            </button>
                        @else
                            <div></div>
                            <button
                                wire:click="activate('{{ $item['build'] }}')"
                                wire:loading.attr="disabled"
                                class="px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-sm shadow-red-950/40">
                                <span wire:loading.remove wire:target="activate('{{ $item['build'] }}')">ACTIVATE</span>
                                <span wire:loading wire:target="activate('{{ $item['build'] }}')">...</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
