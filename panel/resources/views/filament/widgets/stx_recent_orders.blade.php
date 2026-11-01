<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white shrink-0" style="background: linear-gradient(135deg, #3b82f6, #6366f1); box-shadow: 0 0 14px rgba(59, 130, 246, 0.35);">
                        <x-filament::icon icon="tabler-shopping-cart" class="w-4 h-4" />
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-extrabold text-sm text-white tracking-tight truncate">Recent Customer Deployments & Orders</h3>
                        <p class="text-[11px] text-gray-400 truncate">Live order flow and transaction receipts</p>
                    </div>
                </div>

                <a
                    href="/admin/hosting-orders"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-300 transition hover:text-white shrink-0"
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);"
                >
                    <span>View All Orders</span>
                    <x-filament::icon icon="tabler-arrow-right" class="w-3.5 h-3.5" />
                </a>
            </div>
        </x-slot>

        <div class="overflow-x-auto -mx-3 -mb-3 sm:mx-0 sm:mb-0">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b" style="border-color: rgba(255, 255, 255, 0.08);">
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400 whitespace-nowrap">Order #</th>
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400">Customer</th>
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400">Product / Tier</th>
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400 whitespace-nowrap">Amount (LKR)</th>
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400 whitespace-nowrap">Status</th>
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400 whitespace-nowrap">Ordered On</th>
                        <th class="py-2.5 px-3 font-extrabold uppercase tracking-wider text-[10px] text-gray-400 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255, 255, 255, 0.04);">
                    @forelse($orders as $order)
                        @php
                            $statusValue = $order->status instanceof \BackedEnum ? $order->status->value : (string) $order->status;
                            $isPaid = in_array($statusValue, ['active', 'provisioning', 'completed']);
                            $isPending = in_array($statusValue, ['pending_payment', 'pending_verification']);
                            $statusColor = $isPaid ? '#10b981' : ($isPending ? '#f59e0b' : '#f43f5e');
                            $statusBg = $isPaid ? 'rgba(16, 185, 129, 0.15)' : ($isPending ? 'rgba(245, 158, 11, 0.15)' : 'rgba(244, 63, 94, 0.15)');
                            $statusBorder = $isPaid ? 'rgba(16, 185, 129, 0.3)' : ($isPending ? 'rgba(245, 158, 11, 0.3)' : 'rgba(244, 63, 94, 0.3)');
                        @endphp
                        <tr class="transition hover:bg-white/[0.02]">
                            <td class="py-3 px-3 font-mono font-bold text-indigo-400 whitespace-nowrap">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-white">{{ $order->user->name ?? 'Guest Client' }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $order->user->email ?? '—' }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-gray-200">{{ $order->plan->name ?? 'Custom Plan' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->plan->product->name ?? 'Game Server' }}</div>
                            </td>
                            <td class="py-3 px-3 font-bold font-mono text-white whitespace-nowrap">
                                Rs. {{ number_format($order->total_amount_lkr, 2) }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1.5" style="color: {{ $statusColor }}; background: {{ $statusBg }}; border: 1px solid {{ $statusBorder }};">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: {{ $statusColor }};"></span>
                                    <span>{{ ucwords(str_replace('_', ' ', $statusValue)) }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-3 text-gray-400 text-[11px] whitespace-nowrap">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3 px-3 text-right whitespace-nowrap">
                                <a
                                    href="/order/invoice/{{ $order->uuid }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold text-indigo-300 transition hover:text-white"
                                    style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3);"
                                >
                                    <x-filament::icon icon="tabler-file-invoice" class="w-3.5 h-3.5" />
                                    <span>Invoice</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-500">
                                No customer orders recorded yet. Orders placed through the storefront appear here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>