<x-filament-panels::page>
    <div class="space-y-6 max-w-4xl">
        {{-- Explanation Card --}}
        <div class="p-5 rounded-lg bg-[#0F1013] border border-[#1E2025] flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-950/60 border border-red-800/80 text-red-400 flex items-center justify-center text-xl shrink-0">
                <i class="ti ti-adjustments-horizontal"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">MTA:SA Server Configuration (mtaserver.conf)</h3>
                <p class="text-xs text-gray-400 mt-1">
                    Manage core Grand Theft Auto: San Andreas multiplayer server parameters. Changes are automatically synchronized with <code class="text-gray-300">mods/deathmatch/mtaserver.conf</code>.
                </p>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="save" class="p-6 rounded-lg bg-[#0F1013] border border-[#1E2025] space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                    Server Display Name
                </label>
                <input
                    type="text"
                    wire:model="servername"
                    placeholder="My STX MTA:SA Server"
                    class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-sans focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                />
                <p class="text-[11px] text-gray-500 mt-1">Title displayed in the MTA server masterlist.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Max Player Slots
                    </label>
                    <input
                        type="number"
                        min="1"
                        max="1024"
                        wire:model="maxplayers"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        FPS Limit (25 - 100)
                    </label>
                    <input
                        type="number"
                        min="25"
                        max="100"
                        wire:model="fpslimit"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                    <p class="text-[11px] text-gray-500 mt-1">Default 60 FPS recommended for smooth sync.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        HTTP Download Port
                    </label>
                    <input
                        type="number"
                        wire:model="httpport"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                    <p class="text-[11px] text-gray-500 mt-1">Usually Server Port + 2 (e.g. 22005).</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Server Password (Optional)
                    </label>
                    <input
                        type="text"
                        wire:model="password"
                        placeholder="Leave blank for public server"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                </div>
            </div>

            <div class="pt-3 border-t border-[#1E2025] grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model="voice"
                        class="w-4 h-4 rounded bg-[#090A0C] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Enable Built-in Voice Chat</span>
                        <p class="text-[11px] text-gray-500">Activates 3D proximity voice communication in-game.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model="ase"
                        class="w-4 h-4 rounded bg-[#090A0C] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Broadcast to Public Masterlist (ASE)</span>
                        <p class="text-[11px] text-gray-500">Makes your server visible in the MTA in-game browser.</p>
                    </div>
                </label>
            </div>

            <div class="pt-4 flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-sm">
                    Save MTA Configuration
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>

