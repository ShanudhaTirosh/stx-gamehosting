<x-filament-panels::page>
    <div class="space-y-6 max-w-4xl">
        {{-- Status Card --}}
        <div class="p-5 rounded-lg bg-[#0F1013] border border-[#1E2025] flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-950/60 border border-red-800/80 text-red-400 flex items-center justify-center text-xl shrink-0">
                <i class="ti ti-brand-git"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Continuous Git Deployment</h3>
                <p class="text-xs text-gray-400 mt-1">
                    Connect your GitHub, GitLab, or Gitea repository. The server can automatically clone and pull the latest code on container boot.
                </p>
                <div class="mt-3 flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-mono font-semibold bg-gray-900 border border-gray-800 text-gray-300">
                        Runtime: <strong class="text-white uppercase">{{ $runtime }}</strong>
                    </span>
                    @if($autoUpdate)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-950/80 border border-emerald-800 text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Auto-Pull on Boot Active
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Repository Settings Form --}}
        <form wire:submit.prevent="saveSettings" class="p-6 rounded-lg bg-[#0F1013] border border-[#1E2025] space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                    Git Repository Address
                </label>
                <input
                    type="text"
                    wire:model="gitAddress"
                    placeholder="https://github.com/username/my-discord-bot.git"
                    class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                />
                <p class="text-[11px] text-gray-500 mt-1">
                    Public or private repository clone URL. If private, configure your Personal Access Token in server startup settings.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Git Branch
                    </label>
                    <input
                        type="text"
                        wire:model="branch"
                        placeholder="main"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                    <p class="text-[11px] text-gray-500 mt-1">Defaults to <code class="text-gray-400">main</code> or <code class="text-gray-400">master</code>.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1.5">
                        Entrypoint File
                    </label>
                    <input
                        type="text"
                        wire:model="mainFile"
                        placeholder="{{ $runtime === 'python' ? 'app.py / bot.py' : 'index.js / src/index.ts' }}"
                        class="w-full px-4 py-2.5 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition"
                    />
                    <p class="text-[11px] text-gray-500 mt-1">The main script file that boots your bot.</p>
                </div>
            </div>

            <div class="pt-2 border-t border-[#1E2025]">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model="autoUpdate"
                        class="w-4 h-4 rounded bg-[#090A0C] border-[#22252B] text-[#C5162D] focus:ring-red-600"
                    />
                    <div>
                        <span class="text-xs font-semibold text-white">Enable Auto-Update on Server Startup</span>
                        <p class="text-[11px] text-gray-500">When enabled, the server automatically executes <code class="text-gray-400">git pull</code> every time it boots up.</p>
                    </div>
                </label>
            </div>

            <div class="pt-3 flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-sm">
                    Save Git Settings
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>

