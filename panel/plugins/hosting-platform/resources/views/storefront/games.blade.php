@extends('hosting-platform::storefront.layout')

@section('title', 'Game Servers & Application Containers Catalog — STX Enterprises Sri Lanka')
@section('meta_description', 'Explore STX Enterprises game servers and cloud containers: Minecraft, Hytale, FiveM, GTA, Discord and Bot hosting with low ping in Sri Lanka and Singapore.')
@section('meta_keywords', 'game servers sri lanka, minecraft server list, fivem server hosting, bot hosting colombo, stx enterprises catalog')

@section('content')
<!-- Catalog Header Section -->
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span class="text-cyan-optic">Catalog</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="stx-display text-4xl sm:text-5xl text-white tracking-tight">
                    Dedicated Game Servers & Compute Containers
                </h1>
                <p class="mt-3 text-base text-gray-400 max-w-2xl leading-relaxed">
                    High-frequency AMD Ryzen 9 7950X compute nodes with Gen4 NVMe enterprise storage, isolated resource limits, and sub-15ms Sri Lanka latency.
                </p>
            </div>

            <!-- Fast Filter & Search -->
            <div class="w-full md:w-80">
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input
                        type="text"
                        id="game-filter-input"
                        onkeyup="filterGames()"
                        placeholder="Filter by game, engine, or bot..."
                        class="stx-input !pl-9 !py-2.5 !text-xs !bg-[#0C1019] !border-[#212B3E]"
                    >
                </div>
            </div>
        </div>

        <!-- Filter Category Tabs -->
        <div class="mt-8 flex flex-wrap gap-2 pt-4 border-t border-[#1D263B]/60" id="category-tabs">
            <button
                type="button"
                onclick="filterCategory('all')"
                class="cat-tab px-3.5 py-1.5 rounded-[2px] text-xs font-mono font-medium border border-cyan-optic bg-cyan-optic/10 text-cyan-optic transition active-tab"
                data-target="all"
            >
                All Instances
            </button>
            @foreach($categories as $cat)
                <button
                    type="button"
                    onclick="filterCategory('{{ $cat->slug }}')"
                    class="cat-tab px-3.5 py-1.5 rounded-[2px] text-xs font-mono font-medium border border-[#1D263B] bg-[#0A0D15] text-gray-400 hover:text-white hover:border-gray-500 transition"
                    data-target="{{ $cat->slug }}"
                >
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>
</section>

<!-- Products Catalog Grid -->
<section class="py-16 bg-[#080B10]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="games-grid">
            @foreach($categories as $category)
                @foreach($category->products as $product)
                    <div
                        class="game-card stx-card p-6 flex flex-col justify-between group"
                        data-category="{{ $category->slug }}"
                        data-title="{{ strtolower($product->name . ' ' . $category->name . ' ' . $product->description) }}"
                    >
                        <div class="space-y-4">
                            <!-- Header: Icon & Category Tag -->
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-2xl group-hover:border-cyan-optic transition">
                                    <i class="ti {{ $product->icon ?: 'ti-device-gamepad-2' }}"></i>
                                </div>
                                <span class="stx-badge bg-[#0E1420] text-gray-400 border border-[#1D263B]">
                                    {{ $category->name }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-xl font-bold text-white group-hover:text-cyan-optic transition">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-400 mt-1.5 leading-relaxed">{{ $product->description }}</p>
                            </div>

                            <!-- Starting Price Box -->
                            @if($product->plans->count() > 0)
                                @php $cheapest = $product->plans->first(); @endphp
                                <div class="p-3.5 rounded-[2px] bg-[#07090E] border border-[#1D263B] flex items-center justify-between">
                                    <div>
                                        <div class="stx-label text-[10px]">Starting from</div>
                                        <div class="stx-mono text-lg font-bold text-white mt-0.5">
                                            Rs. {{ number_format($cheapest->price_lkr, 0) }}
                                            <span class="text-xs text-gray-500 font-normal">/ mo</span>
                                        </div>
                                    </div>
                                    <span class="stx-badge bg-[#0E1420] text-emerald-400 border border-emerald-500/25">
                                        {{ $product->plans->count() }} {{ \Illuminate\Support\Str::plural('Tier', $product->plans->count()) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Technical Features List -->
                            <ul class="space-y-2 text-xs text-gray-300 pt-2 font-mono">
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-cyan-optic text-xs shrink-0"></i>
                                    <span>Ryzen 9 7950X / PCIe 4.0 NVMe</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-cyan-optic text-xs shrink-0"></i>
                                    <span>Sub-15ms Sri Lanka BGP Peering</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-cyan-optic text-xs shrink-0"></i>
                                    <span>Full Web Console, SFTP & Automated Backups</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-cyan-optic text-xs shrink-0"></i>
                                    <span>1.2 Tbps L3/L4/L7 DDoS Mitigation</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Action Link -->
                        <div class="mt-6 pt-6 border-t border-[#1D263B]">
                            <a
                                href="{{ route('hosting.storefront.category', $category->slug) }}"
                                class="w-full stx-btn-cyan text-xs !py-3"
                            >
                                <span>Configure & Order</span>
                                <i class="ti ti-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <div id="no-results" class="hidden text-center py-20 stx-panel">
            <i class="ti ti-search-off text-4xl text-gray-500 mb-3 block"></i>
            <h3 class="text-lg font-bold text-white">No server matching your query</h3>
            <p class="text-xs text-gray-400 mt-1">Try searching for Minecraft, FiveM, or Bot, or browse all categories.</p>
        </div>
    </div>
</section>

<!-- Infrastructure Architecture Pillars -->
<section class="py-16 border-t border-[#1D263B] bg-[#06080D]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stx-panel p-6 space-y-3 bg-[#0A0D15]">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-cpu"></i>
                </div>
                <h4 class="text-base font-bold text-white">Zero CPU Throttling</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    We never artificially throttle your server clock rates or TPS. Compute instances burst up to 5.7 GHz single-core frequency whenever high tick rates or chunk loading are demanded.
                </p>
            </div>

            <div class="stx-panel p-6 space-y-3 bg-[#0A0D15]">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-device-sd-card"></i>
                </div>
                <h4 class="text-base font-bold text-white">Gen4 NVMe Speeds</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Sustained 7,000 MB/s sequential read and write speeds guarantee zero world save lag spikes and instantaneous server startups in under 10 seconds.
                </p>
            </div>

            <div class="stx-panel p-6 space-y-3 bg-[#0A0D15]">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-shield-check"></i>
                </div>
                <h4 class="text-base font-bold text-white">Edge Layer DDoS Defense</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Real-time hardware packet scrubbers continuously analyze incoming UDP/TCP gaming streams, dropping SYN floods, Amplification reflection, and malicious botnet requests before they reach your container.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Client-side Interactive Filter Script -->
<script>
    let currentCategory = 'all';

    function filterCategory(catSlug) {
        currentCategory = catSlug;
        const tabs = document.querySelectorAll('.cat-tab');
        tabs.forEach(tab => {
            if (tab.getAttribute('data-target') === catSlug) {
                tab.className = 'cat-tab px-3.5 py-1.5 rounded-[2px] text-xs font-mono font-medium border border-cyan-optic bg-cyan-optic/10 text-cyan-optic transition active-tab';
            } else {
                tab.className = 'cat-tab px-3.5 py-1.5 rounded-[2px] text-xs font-mono font-medium border border-[#1D263B] bg-[#0A0D15] text-gray-400 hover:text-white hover:border-gray-500 transition';
            }
        });
        filterGames();
    }

    function filterGames() {
        const query = document.getElementById('game-filter-input').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.game-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const matchesCat = (currentCategory === 'all' || card.getAttribute('data-category') === currentCategory);
            const matchesQuery = !query || card.getAttribute('data-title').includes(query);

            if (matchesCat && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noRes = document.getElementById('no-results');
        if (visibleCount === 0) {
            noRes.classList.remove('hidden');
        } else {
            noRes.classList.add('hidden');
        }
    }
</script>
@endsection