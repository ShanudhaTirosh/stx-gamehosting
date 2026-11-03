{{-- Spec table for a collection of HostingPlan. Expects $plans. --}}
@php $fmtGb = fn ($n) => rtrim(rtrim(number_format($n, 1), '0'), '.'); @endphp
<div class="stx-panel overflow-x-auto border-[#1D263B] bg-[#0C1019]">
    <table class="w-full text-left min-w-[700px] text-xs">
        <thead>
            <tr class="border-b border-[#1D263B] text-gray-500 font-mono">
                <th class="stx-label font-medium px-5 py-3.5">Plan Tier</th>
                <th class="stx-label font-medium px-5 py-3.5 text-right">Memory</th>
                <th class="stx-label font-medium px-5 py-3.5 text-right">CPU Core</th>
                <th class="stx-label font-medium px-5 py-3.5 text-right">NVMe Disk</th>
                <th class="stx-label font-medium px-5 py-3.5">Key Features</th>
                <th class="stx-label font-medium px-5 py-3.5 text-right">Monthly (LKR)</th>
                <th class="px-5 py-3.5 text-right"><span class="sr-only">Order</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#1D263B]">
            @foreach($plans as $plan)
                <tr class="stx-row transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-white font-bold text-sm">{{ $plan->name }}</span>
                            @if($plan->display_badge)
                                <span class="stx-badge bg-cyan-optic/10 text-cyan-optic border border-cyan-optic/30 text-[10px]">
                                    {{ $plan->display_badge }}
                                </span>
                            @endif
                        </div>
                        @if($plan->tagline)
                            <div class="text-[11px] text-gray-400 mt-0.5 line-clamp-1">{{ $plan->tagline }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-4 stx-mono text-white text-right whitespace-nowrap font-medium">
                        {{ $fmtGb($plan->memory_gb) }} GB DDR5
                    </td>
                    <td class="px-5 py-4 stx-mono text-white text-right whitespace-nowrap font-medium">
                        {{ $plan->cpu_percent }}%
                    </td>
                    <td class="px-5 py-4 stx-mono text-white text-right whitespace-nowrap font-medium">
                        {{ $fmtGb($plan->disk_gb) }} GB
                    </td>
                    <td class="px-5 py-4 text-gray-400">
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($plan->display_features, 0, 3) as $f)
                                <span class="px-1.5 py-0.5 bg-[#07090E] border border-[#182133] rounded-[2px] text-[10px] text-gray-300">
                                    {{ $f }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="stx-mono text-white font-bold text-sm">Rs. {{ number_format((float) $plan->price_lkr, 0) }}</span>
                        <span class="stx-mono text-[10px] text-gray-500 block">/ {{ strtolower($plan->billing_period->getLabel()) }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('hosting.checkout.show', $plan->slug) }}" class="stx-btn-cyan text-xs !py-1.5 !px-3 font-mono">
                            Order <i class="ti ti-arrow-right text-[10px]"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>