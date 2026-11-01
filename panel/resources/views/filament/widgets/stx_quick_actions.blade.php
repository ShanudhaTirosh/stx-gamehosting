<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-indigo-400 shrink-0" style="background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.25);">
                    <x-filament::icon icon="tabler-terminal-2" class="w-4 h-4" />
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold text-sm text-white tracking-tight truncate">Operations Hub</h3>
                    <p class="text-[11px] text-gray-400 truncate">Executive Command Shortcuts</p>
                </div>
            </div>
        </x-slot>

        <div class="space-y-2.5" style="min-width: 0;">
            <!-- 1. Orders & Invoices -->
            <a href="/admin/hosting-orders" class="flex items-center justify-between p-2.5 rounded-xl transition duration-150 group hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0;">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-indigo-400 shrink-0" style="background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.2);">
                        <x-filament::icon icon="tabler-receipt-2" class="w-3.5 h-3.5" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-white group-hover:text-indigo-300 transition truncate">Orders & Invoices</div>
                        <div class="text-[10px] text-gray-400 truncate">Payment proofs & receipts</div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-amber-300 shrink-0 ml-1.5" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);">
                    Active
                </span>
            </a>

            <!-- 2. Telemetry & Probes -->
            <a href="/admin/status-monitors" class="flex items-center justify-between p-2.5 rounded-xl transition duration-150 group hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0;">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-emerald-400 shrink-0" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.2);">
                        <x-filament::icon icon="tabler-activity-heartbeat" class="w-3.5 h-3.5" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-white group-hover:text-emerald-300 transition truncate">Telemetry Probes</div>
                        <div class="text-[10px] text-gray-400 truncate">8 endpoints active</div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-emerald-300 shrink-0 ml-1.5" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);">
                    Live
                </span>
            </a>

            <!-- 3. Game Eggs & Jars -->
            <a href="/admin/eggs" class="flex items-center justify-between p-2.5 rounded-xl transition duration-150 group hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0;">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-amber-400 shrink-0" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <x-filament::icon icon="tabler-egg" class="w-3.5 h-3.5" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-white group-hover:text-amber-300 transition truncate">Game Eggs & Jars</div>
                        <div class="text-[10px] text-gray-400 truncate">Paper, FiveM, Node.js</div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-gray-300 shrink-0 ml-1.5" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.08);">
                    12 Ready
                </span>
            </a>

            <!-- 4. Support Tickets -->
            <a href="/admin/tickets" class="flex items-center justify-between p-2.5 rounded-xl transition duration-150 group hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0;">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-sky-400 shrink-0" style="background: rgba(14, 165, 233, 0.12); border: 1px solid rgba(14, 165, 233, 0.2);">
                        <x-filament::icon icon="tabler-messages" class="w-3.5 h-3.5" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-white group-hover:text-sky-300 transition truncate">Support Tickets</div>
                        <div class="text-[10px] text-gray-400 truncate">Client helpdesk queue</div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-gray-400 shrink-0 ml-1.5" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08);">
                    Desk
                </span>
            </a>

            <!-- 5. Compute Nodes -->
            <a href="/admin/nodes" class="flex items-center justify-between p-2.5 rounded-xl transition duration-150 group hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0;">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-cyan-400 shrink-0" style="background: rgba(6, 182, 212, 0.12); border: 1px solid rgba(6, 182, 212, 0.2);">
                        <x-filament::icon icon="tabler-server" class="w-3.5 h-3.5" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-white group-hover:text-cyan-300 transition truncate">Cluster Nodes</div>
                        <div class="text-[10px] text-gray-400 truncate">Wings compute daemons</div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-cyan-300 shrink-0 ml-1.5" style="background: rgba(6, 182, 212, 0.12); border: 1px solid rgba(6, 182, 212, 0.25);">
                    Online
                </span>
            </a>

            <!-- 6. Status Incidents -->
            <a href="/admin/status-incidents" class="flex items-center justify-between p-2.5 rounded-xl transition duration-150 group hover:bg-white/[0.04]" style="background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.07); min-width: 0;">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-rose-400 shrink-0" style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.2);">
                        <x-filament::icon icon="tabler-alert-circle" class="w-3.5 h-3.5" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-xs text-white group-hover:text-rose-300 transition truncate">Incident Dispatch</div>
                        <div class="text-[10px] text-gray-400 truncate">Notices & maintenance</div>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-rose-300 shrink-0 ml-1.5" style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.25);">
                    NOC
                </span>
            </a>

            <!-- Trio External Portals (Harmonious STX Glass Styling) -->
            <div class="pt-1.5 grid grid-cols-3 gap-2" style="min-width: 0;">
                <a
                    href="/"
                    target="_blank"
                    class="py-2.5 px-2 rounded-xl text-center font-bold text-xs transition flex items-center justify-center gap-1.5 group hover:scale-[1.02]"
                    style="background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.22); color: #a5b4fc; min-width: 0;"
                >
                    <x-filament::icon icon="tabler-shopping-cart" class="w-3.5 h-3.5 text-indigo-400 shrink-0" />
                    <span class="truncate">Store</span>
                </a>
                <a
                    href="/status"
                    target="_blank"
                    class="py-2.5 px-2 rounded-xl text-center font-bold text-xs transition flex items-center justify-center gap-1.5 group hover:scale-[1.02]"
                    style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.22); color: #6ee7b7; min-width: 0;"
                >
                    <x-filament::icon icon="tabler-radar" class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
                    <span class="truncate">Status</span>
                </a>
                <a
                    href="/help"
                    target="_blank"
                    class="py-2.5 px-2 rounded-xl text-center font-bold text-xs transition flex items-center justify-center gap-1.5 group hover:scale-[1.02]"
                    style="background: rgba(236, 72, 153, 0.08); border: 1px solid rgba(236, 72, 153, 0.22); color: #f472b6; min-width: 0;"
                >
                    <x-filament::icon icon="tabler-help" class="w-3.5 h-3.5 text-pink-400 shrink-0" />
                    <span class="truncate">Help Desk</span>
                </a>
            </div>

            <!-- Cluster Diagnostics & Engine Vitals Card (2x2 Professional Telemetry Grid) -->
            <div class="p-3 rounded-xl mt-2 space-y-2.5 text-xs" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06);">
                <div class="flex items-center justify-between text-[11px] font-bold text-gray-300 pb-1.5 border-b" style="border-color: rgba(255, 255, 255, 0.06);">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full" style="background: #10b981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);"></span>
                        <span>System Vitals</span>
                    </div>
                    <span class="font-mono text-[10px] text-emerald-400 font-semibold">All Systems OK</span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="p-2 rounded-lg" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);">
                        <div class="text-[10px] text-gray-400">Core Engine</div>
                        <div class="font-bold text-white mt-0.5 font-mono">v1.0.0-rc.2</div>
                    </div>
                    <div class="p-2 rounded-lg" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);">
                        <div class="text-[10px] text-gray-400">Database</div>
                        <div class="font-bold text-emerald-400 mt-0.5 font-mono">MariaDB 10.11</div>
                    </div>
                    <div class="p-2 rounded-lg" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);">
                        <div class="text-[10px] text-gray-400">Cache Layer</div>
                        <div class="font-bold text-emerald-400 mt-0.5 font-mono">Redis Online</div>
                    </div>
                    <div class="p-2 rounded-lg" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04);">
                        <div class="text-[10px] text-gray-400">Queue Worker</div>
                        <div class="font-bold text-indigo-300 mt-0.5 font-mono">Running</div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>