<x-filament-panels::page>
    <div class="space-y-6 max-w-4xl">
        {{-- Explanation Card --}}
        <div class="p-5 rounded-lg bg-[#0F1013] border border-[#1E2025] flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-950/60 border border-red-800/80 text-red-400 flex items-center justify-center text-xl shrink-0">
                <i class="ti ti-settings-cog"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Minecraft Server Properties Configurator</h3>
                <p class="text-xs text-gray-400 mt-1">
                    Visual editor for <code class="text-gray-300">server.properties</code>. Configure gameplay modes, offline/cracked auth mode, view distance, and world rules without syntax errors.
                </p>
            </div>
        </div>

        {{-- Configuration Form --}}
        <form wire:submit.prevent="save" class="p-6 rounded-lg bg-[#0F1013] border border-[#1E2025] space-y-6">
            {{-- MOTD with Live Preview --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-semibold text-gray-300 uppercase tracking-wider">
                        Message of the Day (MOTD)
                    </label>
                    <span class="text-[11px] text-gray-500">Supports Minecraft color codes (e.g. &amp;a, &amp;c, &amp;6, &amp;e, &amp;l)</span>
                </div>
                <input
                    type="text"
                    wire:model.live="motd"
                    placeholder="A Minecraft Server hosted by STX Enterprises"
                    class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                />

                {{-- Live In-Game Multiplayer Ping Preview --}}
                <div class="mt-2.5 p-3 rounded-lg bg-[#07080A] border border-[#18191E] flex items-center justify-between font-mono text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 font-bold text-sm">
                            MC
                        </div>
                        <div>
                            <span class="text-white font-bold block">STX Minecraft Server</span>
                            <span class="text-gray-400 text-[11px] block truncate max-w-md">
                                @php
                                    $preview = htmlspecialchars($motd);
                                    $colors = [
                                        '&0' => '<span style="color:#000000">',
                                        '&1' => '<span style="color:#0000AA">',
                                        '&2' => '<span style="color:#00AA00">',
                                        '&3' => '<span style="color:#00AAAA">',
                                        '&4' => '<span style="color:#AA0000">',
                                        '&5' => '<span style="color:#AA00AA">',
                                        '&6' => '<span style="color:#FFAA00">',
                                        '&7' => '<span style="color:#AAAAAA">',
                                        '&8' => '<span style="color:#555555">',
                                        '&9' => '<span style="color:#5555FF">',
                                        '&a' => '<span style="color:#55FF55">',
                                        '&b' => '<span style="color:#55FFFF">',
                                        '&c' => '<span style="color:#FF5555">',
                                        '&d' => '<span style="color:#FF55FF">',
                                        '&e' => '<span style="color:#FFFF55">',
                                        '&f' => '<span style="color:#FFFFFF">',
                                        '&l' => '<span style="font-weight:bold">',
                                        '&r' => '</span>',
                                    ];
                                    $preview = str_ireplace(array_keys($colors), array_values($colors), $preview);
                                @endphp
                                {!! $preview !!}
                            </span>
                        </div>
                    </div>
                    <span class="text-emerald-400 font-bold text-xs">20ms ●●●●●</span>
                </div>
            </div>

            {{-- Game Mode & Difficulty --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Default Game Mode
                    </label>
                    <select
                        wire:model="gamemode"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition">
                        <option value="survival">Survival</option>
                        <option value="creative">Creative</option>
                        <option value="adventure">Adventure</option>
                        <option value="spectator">Spectator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Difficulty
                    </label>
                    <select
                        wire:model="difficulty"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition">
                        <option value="peaceful">Peaceful</option>
                        <option value="easy">Easy</option>
                        <option value="normal">Normal</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            {{-- Max Players & View Distance --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Max Player Slots
                    </label>
                    <input
                        type="number"
                        min="1"
                        max="10000"
                        wire:model="maxPlayers"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        View Distance (Chunks)
                    </label>
                    <input
                        type="number"
                        min="3"
                        max="32"
                        wire:model="viewDistance"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Simulation Distance
                    </label>
                    <input
                        type="number"
                        min="3"
                        max="32"
                        wire:model="simulationDistance"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                </div>
            </div>

            {{-- Toggles Grid --}}
            <div class="pt-4 border-t border-[#1E2025] grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-[#090A0C] border border-[#1E2025] hover:border-[#2A2D34] transition">
                    <input
                        type="checkbox"
                        wire:model="onlineMode"
                        class="w-4 h-4 rounded bg-[#07080A] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Online Mode (Official Mojang Auth)</span>
                        <p class="text-[11px] text-gray-500">Uncheck to allow offline / cracked accounts to join.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-[#090A0C] border border-[#1E2025] hover:border-[#2A2D34] transition">
                    <input
                        type="checkbox"
                        wire:model="pvp"
                        class="w-4 h-4 rounded bg-[#07080A] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Player vs Player (PvP) Combat</span>
                        <p class="text-[11px] text-gray-500">Allow players to damage each other in survival.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-[#090A0C] border border-[#1E2025] hover:border-[#2A2D34] transition">
                    <input
                        type="checkbox"
                        wire:model="commandBlocks"
                        class="w-4 h-4 rounded bg-[#07080A] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Enable Command Blocks</span>
                        <p class="text-[11px] text-gray-500">Allows command block execution for custom maps and minigames.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-[#090A0C] border border-[#1E2025] hover:border-[#2A2D34] transition">
                    <input
                        type="checkbox"
                        wire:model="whitelist"
                        class="w-4 h-4 rounded bg-[#07080A] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Server Whitelist Enforcement</span>
                        <p class="text-[11px] text-gray-500">Only players listed in whitelist.json can connect.</p>
                    </div>
                </label>
            </div>

            <div class="pt-4 flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-sm">
                    Save Properties
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>

