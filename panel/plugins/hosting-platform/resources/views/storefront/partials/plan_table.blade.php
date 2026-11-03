{{-- Spec table for a collection of HostingPlan. Expects $plans. --}}
@php $fmtGb = fn ($n) => rtrim(rtrim(number_format($n, 1), '0'), '.'); @endphp
<div class="stx-panel overflow-x-auto">
    <table class="w-full text-left min-w-[680px]">
        <thead>
            <tr class="border-b stx-rule">
                <th class="stx-label font-normal px-4 py-3">Plan</th>
                <th class="stx-label font-normal px-4 py-3 text-right">RAM</th>
                <th class="stx-label font-normal px-4 py-3 text-right">CPU</th>
                <th class="stx-label font-normal px-4 py-3 text-right">Disk</th>
                <th class="stx-label font-normal px-4 py-3">Includes</th>
                <th class="stx-label font-normal px-4 py-3 text-right">Price</th>
                <th class="px-4 py-3"><span class="sr-only">Order</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $plan)
                <tr class="stx-row border-b stx-rule last:border-b-0">
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-white font-semibold text-lg">{{ $plan->name }}</span>
                            @if($plan->display_badge)
                                <span class="stx-label px-1.5 py-0.5 border" style="border-color:var(--acc); color:var(--acc)">{{ $plan->display_badge }}</span>
                            @endif
                        </div>
                        @if($plan->tagline)<div class="text-sm text-[var(--mut)] line-clamp-1">{{ $plan->tagline }}</div>@endif
                    </td>
                    <td class="px-4 py-4 stx-mono text-sm text-white text-right whitespace-nowrap">{{ $fmtGb($plan->memory_gb) }} GB</td>
                    <td class="px-4 py-4 stx-mono text-sm text-white text-right">{{ $plan->cpu_percent }}%</td>
                    <td class="px-4 py-4 stx-mono text-sm text-white text-right whitespace-nowrap">{{ $fmtGb($plan->disk_gb) }} GB</td>
                    <td class="px-4 py-4 text-sm text-[var(--mut)]">{{ collect($plan->display_features)->slice(3, 3)->implode(' · ') }}</td>
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                        <span class="stx-mono text-white">Rs. {{ number_format((float) $plan->price_lkr, 0) }}</span>
                        <span class="stx-mono text-xs text-[var(--mut)]">/ {{ strtolower($plan->billing_period->getLabel()) }}</span>
                    </td>
                    <td class="px-4 py-4 text-right"><a href="{{ route('hosting.checkout.show', $plan->slug) }}" class="stx-btn !py-2 text-sm">Order</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
