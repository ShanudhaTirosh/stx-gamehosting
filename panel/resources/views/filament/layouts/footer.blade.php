<footer class="mt-auto border-t border-gray-200 dark:border-white/5 py-6 px-4 text-center space-y-3 text-xs text-gray-500 dark:text-gray-400">
    {{ \Filament\Support\Facades\FilamentView::renderHook(\App\Enums\CustomRenderHooks::FooterStart->value) }}

    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 font-medium">
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="{{ url('/') }}" target="_blank">
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="tabler-home" class="w-3.5 h-3.5" />
                Storefront
            </span>
        </a>
        <a class="hover:text-emerald-500 dark:hover:text-emerald-400 transition" href="{{ url('/status') }}" target="_blank">
            <span class="inline-flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Network Telemetry & Status
            </span>
        </a>
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="{{ url('/customer-orders') }}">
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="tabler-receipt-2" class="w-3.5 h-3.5" />
                My Billing & Orders
            </span>
        </a>
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="{{ url('/help') }}" target="_blank">
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="tabler-help" class="w-3.5 h-3.5" />
                Knowledge Base & Help
            </span>
        </a>
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="{{ url('/contact') }}" target="_blank">
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="tabler-headset" class="w-3.5 h-3.5" />
                Client Helpdesk
            </span>
        </a>
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="https://info.shanutechx.com" target="_blank" rel="noopener">
            <span class="inline-flex items-center gap-1.5">
                <x-filament::icon icon="tabler-book" class="w-3.5 h-3.5" />
                Documentation
            </span>
        </a>
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="{{ url('/terms') }}" target="_blank">
            Terms & SLA
        </a>
        <a class="hover:text-primary-500 dark:hover:text-primary-400 transition" href="{{ url('/privacy') }}" target="_blank">
            Privacy Policy
        </a>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-2 text-[11px] text-gray-400 dark:text-gray-500">
        <a class="font-semibold hover:text-gray-600 dark:hover:text-gray-300 transition" href="https://info.shanutechx.com" target="_blank">
            &copy; {{ date('Y') }} STX Enterprises Lanka (Pvt) Ltd. All rights reserved.
        </a>
        <span class="hidden sm:inline">&bull;</span>
        <span>High-Performance Cloud & Game Server Infrastructure (Colombo DC & Singapore Equinix)</span>

        @if(config('app.debug'))
            <span class="hidden sm:inline">&bull;</span>
            <div class="inline-flex items-center space-x-1 font-mono text-[10px]">
                <x-filament::icon
                    :icon="'tabler-clock'"
                    class="w-3 h-3 text-gray-400 dark:text-gray-500"
                />
                <span>{{ round(microtime(true) - LARAVEL_START, 3) }}s</span>
            </div>
        @endif
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\App\Enums\CustomRenderHooks::FooterEnd->value) }}
</footer>
