<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Explanation Banner --}}
        <div class="p-5 rounded-2xl bg-[#0f1115] border border-gray-800 text-sm flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-950/60 border border-blue-800/80 text-blue-400 flex items-center justify-center text-xl shrink-0">
                <i class="ti ti-binary"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">FXServer Artifacts Manager</h3>
                <p class="text-gray-400 mt-1">
                    Artifacts are the core Linux binary builds of FXServer published by Cfx.re. Keeping your artifacts up to date ensures server stability, security patches, and compatibility with the latest OneSync features.
                </p>
                <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-950/80 border border-blue-800 text-xs font-mono font-bold text-blue-300">
                    Active Configured Target: {{ $currentVersion }}
                </div>
            </div>
        </div>

        {{-- Channel Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Recommended Release --}}
            <div class="p-6 rounded-2xl bg-[#0B0C0E] border {{ $currentVersion === 'recommended' ? 'border-emerald-500/80 shadow-lg shadow-emerald-950/30' : 'border-[#1E2024]' }} flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold tracking-wider uppercase text-emerald-400 bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-1 rounded-full">
                            ★ Recommended Stable
                        </span>
                        @if($currentVersion === 'recommended')
                            <span class="text-xs font-bold text-emerald-400 flex items-center gap-1">
                                <i class="ti ti-check"></i> Active Target
                            </span>
                        @endif
                    </div>
                    <h4 class="text-xl font-bold text-white mt-4">Production Artifacts</h4>
                    <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                        Thoroughly tested and verified stable release for production live roleplay servers. Recommended for minimal crash risk.
                    </p>
                    <div class="mt-4 p-3 rounded-lg bg-black/40 border border-gray-800 text-xs font-mono text-gray-300">
                        Build: {{ $artifacts['recommended'] ?? 'recommended' }}
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-900 flex justify-end">
                    @if($currentVersion === 'recommended')
                        <button type="button" disabled class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-emerald-800/60 cursor-default">
                            Current
                        </button>
                    @else
                        <button
                            wire:click="setVersion('recommended')"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-emerald-600 hover:bg-emerald-500 active:scale-95 transition shadow-md shadow-emerald-950/40">
                            Switch to Recommended
                        </button>
                    @endif
                </div>
            </div>

            {{-- Latest Release --}}
            <div class="p-6 rounded-2xl bg-[#0B0C0E] border {{ $currentVersion === 'latest' ? 'border-amber-500/80 shadow-lg shadow-amber-950/30' : 'border-[#1E2024]' }} flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold tracking-wider uppercase text-amber-400 bg-amber-950/60 border border-amber-800/60 px-2.5 py-1 rounded-full">
                            ⚡ Bleeding Edge
                        </span>
                        @if($currentVersion === 'latest')
                            <span class="text-xs font-bold text-amber-400 flex items-center gap-1">
                                <i class="ti ti-check"></i> Active Target
                            </span>
                        @endif
                    </div>
                    <h4 class="text-xl font-bold text-white mt-4">Latest Build</h4>
                    <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                        Contains the newest features and hotfixes directly from Cfx.re git master. Recommended for development and testing.
                    </p>
                    <div class="mt-4 p-3 rounded-lg bg-black/40 border border-gray-800 text-xs font-mono text-gray-300">
                        Build: {{ $artifacts['latest'] ?? 'latest' }}
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-900 flex justify-end">
                    @if($currentVersion === 'latest')
                        <button type="button" disabled class="px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-amber-800/60 cursor-default">
                            Current
                        </button>
                    @else
                        <button
                            wire:click="setVersion('latest')"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-amber-600 hover:bg-amber-500 active:scale-95 transition shadow-md shadow-amber-950/40">
                            Switch to Latest
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Custom Artifact Input --}}
        <div class="p-6 rounded-2xl bg-[#0B0C0E] border border-[#1E2024]">
            <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-2">Specific Artifact Build Number</h4>
            <p class="text-xs text-gray-400 mb-4">
                If your server resources require an exact historical build, enter the build ID (e.g. <code>12500</code>). You can browse all available builds on the <a href="https://runtime.fivem.net/artifacts/fivem/build_proot_linux/master/" target="_blank" rel="noopener" class="text-red-400 underline underline-offset-2">Cfx.re Artifacts Repository</a>.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <input
                    type="text"
                    wire:model="customArtifact"
                    placeholder="e.g. 12500"
                    class="px-4 py-2.5 rounded-xl bg-black/60 border border-gray-800 text-sm font-mono text-white placeholder-gray-600 focus:outline-none focus:border-red-500 flex-1">
                <button
                    wire:click="applyCustom"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider text-white bg-gray-800 hover:bg-gray-700 transition">
                    Set Custom Build
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
