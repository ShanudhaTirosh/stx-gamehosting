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
<section class="border-b stx-rule">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <nav class="stx-label mb-5" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.games') }}" class="hover:text-white">Servers</a> <span class="mx-1">/</span> <span class="text-white">{{ $category->name }}</span>
        </nav>
        <h1 class="stx-display text-5xl sm:text-7xl text-white">{{ $category->name }} hosting</h1>
        @if($category->description)<p class="mt-5 text-lg text-[var(--mut)] max-w-2xl leading-relaxed">{{ $category->description }}</p>@endif
    </div>
</section>

<section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 space-y-14">
        @forelse($category->products as $product)
            <div>
                <div class="mb-5">
                    <h2 class="text-2xl font-semibold text-white">{{ $product->name }}</h2>
                    @if($product->description)<p class="mt-1 text-[15px] text-[var(--mut)] max-w-2xl">{{ $product->description }}</p>@endif
                </div>
                @if($product->plans->isNotEmpty())
                    @include('hosting-platform::storefront.partials.plan_table', ['plans' => $product->plans])
                @else
                    <div class="stx-panel p-6 text-[var(--mut)]">No plans are available for {{ $product->name }} right now.</div>
                @endif
            </div>
        @empty
            <div class="stx-panel p-8">
                <div class="text-xl font-semibold text-white">Nothing to order here yet.</div>
                <p class="mt-2 text-[var(--mut)]">We have not published {{ $category->name }} plans. <a href="{{ route('hosting.storefront.contact') }}" class="text-[var(--acc)] underline underline-offset-2">Ask us</a> if you need it.</p>
    </div>
        @endforelse
@endsection
