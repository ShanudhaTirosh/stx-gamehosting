<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Custom Package Input Bar --}}
        <div class="p-5 rounded-lg bg-[#0F1013] border border-[#1E2025]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="ti ti-package text-red-400"></i>
                        {{ strtoupper($runtime) }} Package Manager Assistant
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Easily install {{ $runtime === 'python' ? 'PyPI (pip)' : 'NPM' }} dependencies for your bot. Configured packages auto-install upon container boot.
                    </p>
                </div>

                <form wire:submit.prevent="installCustom" class="flex items-center gap-2 w-full md:w-auto">
                    <input
                        type="text"
                        wire:model="customPackage"
                        placeholder="{{ $runtime === 'python' ? 'e.g. pillow / beautifulsoup4' : 'e.g. @types/node / lodash' }}"
                        class="px-3.5 py-2 rounded-lg bg-[#090A0C] border border-[#22252B] text-white text-xs font-mono focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none w-full md:w-64"
                    />
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-[#C5162D] hover:bg-[#A31224] active:scale-95 transition shrink-0">
                        Add Package
                    </button>
                </form>
            </div>
        </div>

        {{-- Popular Package Cards Grid --}}
        <div>
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                Popular {{ strtoupper($runtime) }} Bot Libraries
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($commonPackages as $pkg)
                    <div class="p-4 rounded-lg bg-[#0F1013] border border-[#1E2025] flex flex-col justify-between min-h-[145px] hover:border-[#2E323A] transition">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-mono font-bold text-white">{{ $pkg['name'] }}</span>
                                <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded bg-black/60 border border-gray-800 text-gray-400">
                                    {{ $pkg['category'] }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2.5 leading-relaxed">
                                {{ $pkg['description'] }}
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-between pt-2 border-t border-[#18191E]">
                            <span class="text-[11px] font-mono text-gray-500">{{ $pkg['version'] }}</span>
                            <button
                                type="button"
                                wire:click="install('{{ $pkg['name'] }}')"
                                wire:loading.attr="disabled"
                                class="px-3 py-1.5 rounded-md text-[11px] font-bold uppercase tracking-wider text-white bg-[#1C1E24] hover:bg-[#C5162D] transition">
                                <span wire:loading.remove wire:target="install('{{ $pkg['name'] }}')">+ Add</span>
                                <span wire:loading wire:target="install('{{ $pkg['name'] }}')">...</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
