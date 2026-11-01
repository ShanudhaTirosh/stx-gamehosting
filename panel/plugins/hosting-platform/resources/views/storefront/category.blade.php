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
<section class="py-16 md:py-24 relative overflow-hidden">
    <!-- Ambient Glow Background -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumb & Header -->
        <div class="mb-14">
            <nav class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
                <i class="ti ti-chevron-right text-gray-600"></i>
                <a href="{{ route('hosting.storefront.games') }}" class="hover:text-white transition">Catalog</a>
                <i class="ti ti-chevron-right text-gray-600"></i>
                <span class="text-indigo-400 font-semibold">{{ $category->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-white/10">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/10 border border-indigo-500/20 text-indigo-300">
                        <i class="ti {{ $category->icon ?: 'ti-server-2' }}"></i> {{ $category->badge_text ?: 'Active Game Engine' }}
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">{{ $category->name }} Hosting</h1>
                    <p class="text-sm text-gray-400 max-w-2xl leading-relaxed">{{ $category->description }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-xl glass-card text-xs font-semibold text-gray-300 border border-white/10 flex items-center gap-2">
                        <i class="ti ti-coins text-emerald-400"></i> Local Currency: <strong class="text-white">Sri Lankan Rupees (LKR)</strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- Products & Plans Loop -->
        @foreach($category->products as $product)
            <div class="mb-20">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 text-2xl">
                        <i class="ti {{ $product->icon ?: 'ti-cube' }}"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $product->name }}</h2>
                        <p class="text-xs text-gray-400">{{ $product->description }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($product->plans as $plan)
                        <div class="glass-card rounded-3xl p-6 flex flex-col justify-between transition duration-300 relative border group overflow-hidden {{ $plan->is_featured ? 'border-indigo-500/60 shadow-xl shadow-indigo-500/15 ring-1 ring-indigo-500/30' : 'border-white/10' }}">
                            <!-- Plan Ribbon / Badge -->
                            <div class="absolute top-4 right-4 z-20">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $plan->is_featured ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md shadow-indigo-500/30' : 'bg-white/10 text-gray-300 border border-white/10' }}">
                                    {{ $plan->display_badge }}
                                </span>
                            </div>

                            <div>
                                <!-- Plan Artwork Visual / Banner -->
                                <div class="w-full h-36 rounded-2xl overflow-hidden mb-5 bg-black/40 border border-white/5 relative group-hover:border-indigo-500/30 transition">
                                    <img src="{{ $plan->display_image }}" alt="{{ $plan->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-3 left-3 flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-600/80 backdrop-blur-md flex items-center justify-center text-white text-xs">
                                            <i class="ti {{ $plan->icon ?: 'ti-server-2' }}"></i>
                                        </div>
                                        <span class="text-xs font-bold text-white drop-shadow">{{ $plan->name }}</span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-400 mb-4 line-clamp-2">{{ $plan->tagline }}</div>

                                <!-- Price Tag -->
                                <div class="mb-5 p-4 rounded-2xl bg-black/50 border border-white/5">
                                    <div class="text-3xl font-black font-mono text-white">Rs. {{ number_format((float) $plan->price_lkr, 0) }}</div>
                                    <div class="text-[11px] text-gray-400 font-semibold mt-0.5">per {{ $plan->billing_period->getLabel() }} (No hidden setup fees)</div>
                                </div>

                                <!-- Key Resource Metrics -->
                                <div class="grid grid-cols-2 gap-2 mb-5">
                                    <div class="p-2.5 rounded-xl bg-white/5 border border-white/5 text-center">
                                        <div class="text-[10px] text-gray-400 uppercase font-semibold">RAM</div>
                                        <div class="text-sm font-bold text-indigo-300">{{ number_format($plan->memory_mb / 1024, 0) }} GB</div>
                                    </div>
                                    <div class="p-2.5 rounded-xl bg-white/5 border border-white/5 text-center">
                                        <div class="text-[10px] text-gray-400 uppercase font-semibold">NVMe</div>
                                        <div class="text-sm font-bold text-emerald-300">{{ number_format($plan->disk_mb / 1024, 0) }} GB</div>
                                    </div>
                                </div>

                                <!-- Feature Bullets Checklist -->
                                <div class="border-t border-white/5 pt-4">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2.5 block">Plan Inclusions:</span>
                                    <ul class="space-y-2 text-xs text-gray-300">
                                        @foreach($plan->display_features as $feature)
                                            <li class="flex items-start gap-2">
                                                <i class="ti ti-circle-check text-emerald-400 text-sm shrink-0 mt-0.5"></i>
                                                <span class="leading-tight">{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="pt-6 mt-6 border-t border-white/10">
                                <a href="{{ route('hosting.checkout.show', $plan->slug) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-indigo-600 hover:bg-indigo-500 transition shadow-lg shadow-indigo-600/30 group-hover:scale-[1.02]">
                                    Deploy Instance <i class="ti ti-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center glass-card rounded-3xl text-sm text-gray-500">
                            No plans configured for this product yet.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
