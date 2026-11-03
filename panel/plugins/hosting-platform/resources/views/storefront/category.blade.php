@extends('hosting-platform::storefront.layout')

@section('title', $category->getSeoMetaTitle())
@section('meta_description', $category->getSeoMetaDescription())
@section('meta_keywords', $category->getSeoKeywords())
@section('og_image', url($category->getOgImage()))

@section('extra_schema')
@php
    $itemList = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => [],
    ];
    $pos = 1;
    foreach ($category->products as $product) {
        foreach ($product->plans as $plan) {
            $itemList['itemListElement'][] = [
                '@type' => 'Product',
                'position' => $pos++,
                'name' => $plan->name . ' - ' . $product->name,
                'description' => $plan->tagline ?: $plan->getSeoMetaDescription(),
                'image' => url($plan->display_image),
                'sku' => $plan->slug,
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (string) $plan->price_lkr,
                    'priceCurrency' => 'LKR',
                    'availability' => 'https://schema.org/InStock',
                    'url' => route('hosting.checkout.show', $plan->slug),
                ],
            ];
        }
    }
@endphp
<script type="application/ld+json">
{!! json_encode($itemList, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<!-- Category Header -->
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <a href="{{ route('hosting.storefront.games') }}" class="hover:text-white transition">Servers</a>
            <span>/</span>
            <span class="text-cyan-optic">{{ $category->name }}</span>
        </nav>

        <h1 class="stx-display text-4xl sm:text-6xl text-white tracking-tight">
            {{ $category->name }} Server Hosting
        </h1>
        @if($category->description)
            <p class="mt-4 text-base sm:text-lg text-gray-400 max-w-2xl leading-relaxed">
                {{ $category->description }}
            </p>
        @endif

        <div class="mt-6 flex flex-wrap items-center gap-4 text-xs font-mono text-gray-400">
            <span class="flex items-center gap-1.5 text-emerald-400"><span class="stx-dot bg-emerald-400"></span> Colombo Low-Latency Cluster</span>
            <span>•</span>
            <span>NVMe Gen4 Storage</span>
            <span>•</span>
            <span>Billed in Sri Lankan Rupees</span>
        </div>
    </div>
</section>

<!-- Products & Spec Tables -->
<section class="py-16 bg-[#080B10]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        @forelse($category->products as $product)
            <div>
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white flex items-center gap-2.5">
                            <i class="ti {{ $product->icon ?: 'ti-server-2' }} text-cyan-optic"></i>
                            <span>{{ $product->name }}</span>
                        </h2>
                        @if($product->description)
                            <p class="mt-1 text-xs text-gray-400 max-w-2xl leading-relaxed">{{ $product->description }}</p>
                        @endif
                    </div>
                    <span class="stx-badge bg-[#0E1420] text-gray-400 border border-[#1D263B]">
                        {{ $product->plans->count() }} Available {{ \Illuminate\Support\Str::plural('Tier', $product->plans->count()) }}
                    </span>
                </div>

                @if($product->plans->isNotEmpty())
                    @include('hosting-platform::storefront.partials.plan_table', ['plans' => $product->plans])
                @else
                    <div class="stx-panel p-6 text-xs text-gray-400">No active plans are currently listed for {{ $product->name }}.</div>
                @endif
            </div>
        @empty
            <div class="stx-panel p-8 text-center max-w-xl mx-auto">
                <i class="ti ti-package-off text-3xl text-gray-500 mb-2 block"></i>
                <div class="text-lg font-bold text-white">No plans published yet.</div>
                <p class="mt-2 text-xs text-gray-400 leading-relaxed">
                    We have not published {{ $category->name }} plans yet.
                    <a href="{{ route('hosting.storefront.contact') }}" class="text-cyan-optic hover:underline">Contact our NOC desk</a>
                    if you need a custom allocation.
                </p>
            </div>
        @endforelse
    </div>
</section>
@endsection