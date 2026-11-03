<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Explanation Banner --}}
        <div class="p-5 rounded-2xl bg-[#0f1115] border border-gray-800 text-sm flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-950/60 border border-red-800/80 text-red-400 flex items-center justify-center text-xl shrink-0">
                <i class="ti ti-world"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">GTA V GameBuild DLC Selector</h3>
                <p class="text-gray-400 mt-1">
                    Enforcing a game build allows your FiveM server to access newer GTA Online DLC vehicles, weapons, clothing, and map interiors.
                    Clicking <strong class="text-white">Activate</strong> updates <code class="text-red-400 bg-black/40 px-1.5 py-0.5 rounded text-xs">set sv_enforceGameBuild &lt;build&gt;</code> in your <code class="text-white">server.cfg</code>.
                </p>
                @if($activeBuild)
                    <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-800 text-xs font-mono font-bold text-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Currently Enforced Build: {{ $activeBuild }}
                    </div>
                @else
                    <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-900 border border-gray-700 text-xs font-mono text-gray-400">
                        No specific GameBuild enforced in server.cfg (defaults to 1604)
                    </div>
                @endif
            </div>
        </div>

        {{-- GameBuild Cards Grid matching user screenshot --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($builds as $item)
                @php
                    $isActive = ($activeBuild === $item['build']);
                @endphp
                <div class="p-5 rounded-xl bg-[#0B0C0E] border {{ $isActive ? 'border-red-600 shadow-lg shadow-red-950/30' : 'border-[#1E2024]' }} flex flex-col justify-between transition hover:border-[#2E3238] min-h-[170px]">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">{{ $item['name'] }}</span>
                            <span class="text-[11px] font-mono font-semibold px-2 py-0.5 rounded bg-black/50 text-gray-400 border border-gray-800">b{{ $item['build'] }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                            {{ $item['description'] }}
                        </p>
                    </div>

                    <div class="mt-5 flex items-center justify-between pt-2">
                        @if($isActive)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400">
                                <i class="ti ti-check"></i> Activated
                            </span>
                            <button type="button" disabled class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-emerald-700/60 cursor-default">
                                Active
                            </button>
                        @else
                            <span class="text-[11px] text-gray-500">{{ $item['dlc_release'] }}</span>
                            <button
                                wire:click="activate('{{ $item['build'] }}')"
                                wire:loading.attr="disabled"
                                class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-md shadow-red-950/40">
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
