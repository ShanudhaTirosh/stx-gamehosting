<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Status Banner --}}
        <div class="p-6 rounded-2xl bg-[#0f1115] border border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-950/70 border border-red-800/80 text-red-400 flex items-center justify-center text-2xl shrink-0">
                    <i class="ti ti-dashboard"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">txAdmin Management Suite</h3>
                    <p class="text-xs text-gray-400 mt-1 max-w-xl">
                        txAdmin is the official web-based administration panel built directly into FXServer for recipe deployment (QBCore, ESX, Qbox), player management, live map monitoring, and scheduled restarts.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button
                    wire:click="toggleTxAdmin"
                    class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider {{ ($txInfo['is_enabled'] ?? false) ? 'bg-red-950/80 border border-red-800 text-red-300 hover:bg-red-900' : 'bg-emerald-950/80 border border-emerald-800 text-emerald-300 hover:bg-emerald-900' }} transition">
                    {{ ($txInfo['is_enabled'] ?? false) ? 'Disable txAdmin' : 'Enable txAdmin' }}
                </button>
            </div>
        </div>

        {{-- Connection & Launcher Card --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Port & Launch Column --}}
            <div class="lg:col-span-2 p-6 rounded-2xl bg-[#0B0C0E] border border-[#1E2024] space-y-5">
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400">Web Interface Access</h4>

                @if(!empty($txInfo['txadmin_url']))
                    <div class="p-5 rounded-xl bg-gradient-to-r from-red-950/40 via-black to-black border border-red-900/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-mono font-bold text-emerald-400 flex items-center gap-1.5 mb-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> txAdmin Port Assigned: {{ $txInfo['txadmin_port'] }}
                            </span>
                            <div class="text-base font-mono font-bold text-white select-all break-all">
                                {{ $txInfo['txadmin_url'] }}
                            </div>
                        </div>

                        <a
                            href="{{ $txInfo['txadmin_url'] }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-lg shadow-red-950/50 shrink-0">
                            <span>Open txAdmin</span>
                            <i class="ti ti-arrow-up-right text-base"></i>
                        </a>
                    </div>
                @else
                    <div class="p-5 rounded-xl bg-amber-950/30 border border-amber-800/60 text-sm">
                        <div class="flex items-center gap-2 text-amber-400 font-bold mb-2">
                            <i class="ti ti-alert-triangle text-lg"></i> Additional Port Allocation Required
                        </div>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            txAdmin runs on a dedicated secondary HTTP port. Currently, this server only has its primary game port ({{ $txInfo['primary_port'] ?? '30120' }}) assigned.
                        </p>
                        <div class="mt-4 pt-3 border-t border-amber-900/40 flex items-center justify-between">
                            <span class="text-xs text-gray-400">Go to server Network settings to assign a port:</span>
                            <a href="/server/{{ $serverUuid }}/allocations" class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-amber-700 hover:bg-amber-600 transition">
                                Assign Port Allocation →
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Status Details --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3 border-t border-gray-900 text-xs">
                    <div>
                        <div class="text-gray-500 font-medium">txAdmin Status</div>
                        <div class="mt-1 font-bold {{ ($txInfo['is_enabled'] ?? false) ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ ($txInfo['is_enabled'] ?? false) ? 'Enabled' : 'Disabled' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 font-medium">Primary Port (FXServer)</div>
                        <div class="mt-1 font-mono font-bold text-white">
                            {{ $txInfo['primary_port'] ?? '30120' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 font-medium">Web Admin Port</div>
                        <div class="mt-1 font-mono font-bold text-white">
                            {{ $txInfo['txadmin_port'] ?? 'Pending Allocation' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4-Step Quick Setup Guide --}}
            <div class="p-6 rounded-2xl bg-[#0B0C0E] border border-[#1E2024] flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">First-Time Setup Guide</h4>
                    <ol class="space-y-4 text-xs text-gray-300">
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-red-950 border border-red-800 text-red-400 flex items-center justify-center font-mono font-bold text-[10px] shrink-0">1</span>
                            <div>
                                <strong class="text-white">Start your server</strong> from the main console page.
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-red-950 border border-red-800 text-red-400 flex items-center justify-center font-mono font-bold text-[10px] shrink-0">2</span>
                            <div>
                                <strong class="text-white">Copy your 4-digit PIN</strong> shown in the server startup logs (e.g. <code>[txAdmin] PIN: 1234</code>).
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-red-950 border border-red-800 text-red-400 flex items-center justify-center font-mono font-bold text-[10px] shrink-0">3</span>
                            <div>
                                <strong class="text-white">Click "Open txAdmin"</strong> above and link your Cfx.re Keymaster account.
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-red-950 border border-red-800 text-red-400 flex items-center justify-center font-mono font-bold text-[10px] shrink-0">4</span>
                            <div>
                                <strong class="text-white">Select a recipe</strong> (QBCore, ESX Legacy, or Custom) to automatically generate your server template!
                            </div>
                        </li>
                    </ol>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-900">
                    <a href="/server/{{ $serverUuid }}/console" class="inline-flex items-center gap-1.5 text-xs text-red-400 hover:text-red-300 transition font-medium">
                        <i class="ti ti-terminal-2"></i> Go to Server Console logs →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
