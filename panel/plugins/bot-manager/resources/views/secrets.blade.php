<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Banner & Quick Presets --}}
        <div class="p-5 rounded-lg bg-[#0F1013] border border-[#1E2025] flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Bot Environment & Secrets Vault (.env)
                </h3>
                <p class="text-xs text-gray-400 mt-1">
                    Manage secret tokens and runtime parameters securely without editing raw files. Sensitive keys are automatically masked.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mr-1">Quick Presets:</span>
                <button type="button" wire:click="quickAdd('DISCORD_TOKEN')" class="px-2.5 py-1 rounded bg-[#181A20] hover:bg-[#252830] border border-[#262830] text-xs font-mono text-gray-300 transition">
                    + DISCORD_TOKEN
                </button>
                <button type="button" wire:click="quickAdd('TELEGRAM_TOKEN')" class="px-2.5 py-1 rounded bg-[#181A20] hover:bg-[#252830] border border-[#262830] text-xs font-mono text-gray-300 transition">
                    + TELEGRAM_TOKEN
                </button>
                <button type="button" wire:click="quickAdd('CLIENT_ID')" class="px-2.5 py-1 rounded bg-[#181A20] hover:bg-[#252830] border border-[#262830] text-xs font-mono text-gray-300 transition">
                    + CLIENT_ID
                </button>
                <button type="button" wire:click="quickAdd('DATABASE_URL')" class="px-2.5 py-1 rounded bg-[#181A20] hover:bg-[#252830] border border-[#262830] text-xs font-mono text-gray-300 transition">
                    + DATABASE_URL
                </button>
                <button type="button" wire:click="quickAdd('BOT_PREFIX')" class="px-2.5 py-1 rounded bg-[#181A20] hover:bg-[#252830] border border-[#262830] text-xs font-mono text-gray-300 transition">
                    + BOT_PREFIX
                </button>
            </div>
        </div>

        {{-- Add New Variable Bar --}}
        <div class="p-4 rounded-lg bg-[#0F1013] border border-[#1E2025]">
            <form wire:submit.prevent="addVariable" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Variable Name</label>
                    <input
                        type="text"
                        wire:model="newKey"
                        placeholder="e.g. DISCORD_TOKEN"
                        class="w-full px-3.5 py-2 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 transition outline-none"
                    />
                </div>
                <div class="flex-2">
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Value</label>
                    <input
                        type="text"
                        wire:model="newValue"
                        placeholder="Paste value or secret token..."
                        class="w-full px-3.5 py-2 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 transition outline-none"
                    />
                </div>
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full md:w-auto px-5 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shadow-sm">
                        Save / Add
                    </button>
                </div>
            </form>
        </div>

        {{-- Existing Variables Table --}}
        <div class="rounded-lg bg-[#0F1013] border border-[#1E2025] overflow-hidden">
            <div class="px-5 py-3.5 border-b border-[#1E2025] flex items-center justify-between">
                <span class="text-xs font-bold text-gray-300 uppercase tracking-wider">
                    Configured Environment Variables ({{ count($variables) }})
                </span>
                <span class="text-[11px] text-gray-500">Stored in server root <code class="text-gray-400">.env</code></span>
            </div>

            @if(empty($variables))
                <div class="p-8 text-center text-gray-500 text-xs">
                    No variables configured in .env yet. Use the form above or click a Quick Preset to add your bot tokens.
                </div>
            @else
                <div class="divide-y divide-[#1A1C22]">
                    @foreach($variables as $item)
                        @php
                            $isRev = $this->isRevealed($item['key']);
                            $displayVal = ($item['is_secret'] && !$isRev)
                                ? str_repeat('•', min(strlen($item['value']), 24))
                                : $item['value'];
                        @endphp
                        <div class="px-5 py-3 flex items-center justify-between gap-4 hover:bg-[#13151A] transition">
                            <div class="flex items-center gap-3 min-w-[200px]">
                                <span class="text-xs font-mono font-bold text-white tracking-wide">{{ $item['key'] }}</span>
                                @if($item['is_secret'])
                                    <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded bg-red-950/60 border border-red-800/80 text-red-400">
                                        Secret
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 overflow-hidden text-ellipsis font-mono text-xs text-gray-400">
                                <span class="{{ $item['is_secret'] && !$isRev ? 'tracking-widest text-gray-500' : 'text-gray-200' }}">
                                    {{ $displayVal }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($item['is_secret'])
                                    <button
                                        type="button"
                                        wire:click="toggleReveal('{{ $item['key'] }}')"
                                        title="{{ $isRev ? 'Hide value' : 'Show value' }}"
                                        class="p-1.5 rounded hover:bg-[#202228] text-gray-400 hover:text-white transition">
                                        <i class="ti {{ $isRev ? 'ti-eye-off' : 'ti-eye' }} text-sm"></i>
                                    </button>
                                @endif

                                <button
                                    type="button"
                                    wire:click="deleteVariable('{{ $item['key'] }}')"
                                    wire:confirm="Remove {{ $item['key'] }} from .env?"
                                    class="p-1.5 rounded hover:bg-red-950/40 text-gray-400 hover:text-red-400 transition"
                                    title="Delete variable">
                                    <i class="ti ti-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>

