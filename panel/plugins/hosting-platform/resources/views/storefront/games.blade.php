@extends('hosting-platform::storefront.layout')

@section('title', 'Game Servers & Cloud Hosting Catalog — Low Latency Sri Lanka')
@section('meta_description', 'Explore STX Enterprises game servers and cloud containers: Minecraft, Hytale, FiveM, GTA, Discord and Bot hosting with low ping in Sri Lanka and Singapore.')
@section('meta_keywords', 'game servers sri lanka, minecraft server list, fivem server hosting, bot hosting colombo, stx enterprises catalog')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-16 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/25 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>HIGH-FREQUENCY GAME ARCHITECTURE</span>
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight">
            High-Performance Dedicated <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-cyan-300">
                Game Servers & Application Containers
            </span>
        </h1>
        <p class="mt-4 text-base sm:text-lg text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Ultra-fast NVMe storage, unmetered DDoS mitigation, instant version switchers, and local Sri Lankan payment rails.
        </p>
    </div>
</section>

<!-- Games Grid Section -->
<section class="pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                @foreach($category->products as $product)
                    <div class="rounded-3xl bg-surface border border-white/10 p-8 flex flex-col justify-between group hover:border-indigo-500/40 hover:-translate-y-1 transition duration-300 shadow-xl relative overflow-hidden">
                        <!-- Top Accent Line -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition duration-300"></div>

                        <div>
                            <!-- Header Row: Icon + Badge -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center text-indigo-400 text-3xl group-hover:scale-105 transition">
                                    <i class="ti {{ $product->icon ?: 'ti-device-gamepad-2' }}"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider text-indigo-300 bg-indigo-500/15 border border-indigo-500/30">
                                    {{ $category->name }}
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-white group-hover:text-indigo-300 transition">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">{{ $product->description }}</p>

                            <!-- Starting Price -->
                            @if($product->plans->count() > 0)
                                @php $cheapest = $product->plans->first(); @endphp
                                <div class="mt-6 p-4 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] uppercase font-bold text-gray-500">Starting from</div>
                                        <div class="text-lg font-black text-white font-mono">
                                            Rs. {{ number_format($cheapest->price_lkr, 0) }} <span class="text-xs text-gray-400 font-normal">/ mo</span>
                                        </div>
                                    </div>
                                    <span class="text-xs text-emerald-400 font-semibold bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">
                                        {{ $product->plans->count() }} Available Tiers
                                    </span>
                                </div>
                            @endif

                            <!-- Feature Bullets -->
                            <ul class="mt-6 space-y-2 text-xs text-gray-300">
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-emerald-400 text-sm"></i>
                                    <span>Instant automated container allocation</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-emerald-400 text-sm"></i>
                                    <span>Ryzen 9 7950X & NVMe PCIe 4.0 storage</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-emerald-400 text-sm"></i>
                                    <span>Sub-8ms Sri Lanka routing & DDoS shield</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="ti ti-check text-emerald-400 text-sm"></i>
                                    <span>Full Web Console, SFTP & Version Changer</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-8 pt-6 border-t border-white/10">
                            <a
                                href="{{ route('hosting.storefront.category', $category->slug) }}"
                                class="w-full py-3.5 rounded-xl font-bold text-sm text-center text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2"
                            >
                                <span>Configure & Order</span>
                                <i class="ti ti-arrow-right text-sm"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>

<!-- Enterprise Hardware Guarantee Section -->
<section class="py-20 border-t border-white/10 bg-[#05070B]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-surface border border-white/5 space-y-3">
                <i class="ti ti-cpu text-4xl text-indigo-400"></i>
                <h4 class="text-lg font-bold text-white">Uncapped CPU Bursting</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    We never artificially throttle your server tickrates. Burst up to 5.7 GHz whenever world chunk generation or boss battles demand high compute.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-surface border border-white/5 space-y-3">
                <i class="ti ti-database text-4xl text-purple-400"></i>
                <h4 class="text-lg font-bold text-white">Gen4 NVMe Speeds</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Sustained 7,000 MB/s read and write performance ensures zero world save lag spikes and instantaneous server reboots in seconds.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-surface border border-white/5 space-y-3">
                <i class="ti ti-shield-check text-4xl text-emerald-400"></i>
                <h4 class="text-lg font-bold text-white">Automatic L3/L4/L7 Defense</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Our edge scrubbers inspect inbound gaming packets to drop UDP floods, SYN reflection, and botnets before they reach your container.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
