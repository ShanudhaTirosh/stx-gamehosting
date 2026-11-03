@extends('hosting-platform::storefront.layout')

@section('title', 'High-Frequency Game & Cloud Server Hosting — STX Enterprises Sri Lanka')
@section('meta_description', 'Deploy low-latency Minecraft, FiveM, Hytale, and cloud servers in Sri Lanka & Singapore. Instant auto-provisioning with 99.98% SLA and local bank rails.')
@section('meta_keywords', 'game server hosting, minecraft server sri lanka, fivem hosting colombo, cloud vps sri lanka, low latency game hosting, stx enterprises')

@section('content')
<!-- Hero Section: Precision Engineered Infrastructure -->
<section class="border-b border-[#1D263B] relative bg-[#07090E]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20">
        <div class="grid lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2.5 px-3 py-1 rounded-[2px] bg-[#0E1420] border border-[#212B3E] text-xs font-mono text-cyan-optic">
                    <span class="stx-dot bg-cyan-optic"></span>
                    <span>SRI LANKA BARE-METAL COMPUTE CLUSTER</span>
                </div>

                <h1 class="stx-display text-4xl sm:text-6xl text-white tracking-tight leading-[1.08]">
                    High-frequency game & cloud hosting. <br>
                    <span class="text-gray-400">Engineered in Colombo.</span>
                </h1>

                <p class="text-base sm:text-lg text-gray-300 max-w-2xl leading-relaxed">
                    Deploy bare-metal game servers and application containers on dedicated AMD Ryzen 9 7950X nodes. Direct peering with SLT-Mobitel and Dialog Telecom for sub-15ms nationwide latency.
                </p>

                <!-- Action CTAs -->
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('hosting.storefront.games') }}" class="stx-btn-cyan text-sm !px-6 !py-3.5">
                        <i class="ti ti-server"></i> Explore Server Catalog
                    </a>
                    <a href="{{ route('hosting.status.index') }}" class="stx-btn-outline text-sm !px-6 !py-3.5 font-mono">
                        <span class="stx-dot bg-emerald-400"></span> Live Node Telemetry
                    </a>
                </div>

                <!-- Fast Search Bar -->
                <div class="pt-4 max-w-lg">
                    <form action="{{ route('hosting.storefront.games') }}" method="GET" class="relative">
                        <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input
                            type="search"
                            name="search"
                            placeholder="Find servers: Minecraft, FiveM, Node.js Bot, Rust, VPS..."
                            class="stx-input !pl-10 !py-2.5 !text-xs !bg-[#0A0D15] !border-[#1D263B]"
                        >
                    </form>
                </div>
            </div>

            <!-- Right Hero: Interactive Datacenter Latency & Telemetry Probe (5 Cols) -->
            <div class="lg:col-span-5">
                <div class="stx-panel p-6 border-[#243047] space-y-5 bg-[#0C1019]">
                    <div class="flex items-center justify-between border-b border-[#1D263B] pb-4">
                        <div class="flex items-center gap-2">
                            <span class="stx-dot bg-emerald-400"></span>
                            <span class="stx-label text-white">Live Node Telemetry</span>
                        </div>
                        <span class="stx-badge bg-emerald-950/40 text-emerald-300 border border-emerald-500/25">
                            99.98% SLA OK
                        </span>
                    </div>

                    <!-- Region Selector Tabs -->
                    <div>
                        <div class="stx-label mb-2">Select Datacenter Region</div>
                        <div class="grid grid-cols-2 gap-2" id="node-selector">
                            <button
                                type="button"
                                onclick="switchNode('colombo')"
                                id="btn-colombo"
                                class="p-3 text-left rounded-[2px] border border-cyan-optic bg-cyan-optic/10 text-white font-mono text-xs transition"
                            >
                                <div class="font-bold text-cyan-optic">Colombo, LK</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">SLT & Dialog IX</div>
                            </button>
                            <button
                                type="button"
                                onclick="switchNode('singapore')"
                                id="btn-singapore"
                                class="p-3 text-left rounded-[2px] border border-[#1D263B] bg-[#0A0E17] text-gray-400 font-mono text-xs hover:border-gray-500 transition"
                            >
                                <div class="font-bold text-gray-300">Singapore, SG</div>
                                <div class="text-[11px] text-gray-500 mt-0.5">Equinix SG1 Hub</div>
                            </button>
                        </div>
                    </div>

                    <!-- Telemetry Metrics Grid -->
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="p-3 rounded-[2px] bg-[#080B10] border border-[#182133]">
                            <div class="stx-label text-[10px]">Average Ping (SL)</div>
                            <div id="stat-ping" class="stx-mono text-2xl font-bold text-cyan-optic mt-0.5">&lt; 12 ms</div>
                            <div id="stat-ping-sub" class="text-[11px] text-gray-500 mt-0.5 font-mono">Colombo Metro Loop</div>
                        </div>
                        <div class="p-3 rounded-[2px] bg-[#080B10] border border-[#182133]">
                            <div class="stx-label text-[10px]">Packet Loss</div>
                            <div id="stat-loss" class="stx-mono text-2xl font-bold text-emerald-400 mt-0.5">0.00%</div>
                            <div class="text-[11px] text-gray-500 mt-0.5 font-mono">BGP Multi-homed</div>
                        </div>
                        <div class="p-3 rounded-[2px] bg-[#080B10] border border-[#182133]">
                            <div class="stx-label text-[10px]">Peak Clock Rate</div>
                            <div id="stat-cpu" class="stx-mono text-2xl font-bold text-white mt-0.5">5.7 GHz</div>
                            <div class="text-[11px] text-gray-500 mt-0.5 font-mono">AMD Ryzen 9 7950X</div>
                        </div>
                        <div class="p-3 rounded-[2px] bg-[#080B10] border border-[#182133]">
                            <div class="stx-label text-[10px]">Storage I/O</div>
                            <div id="stat-io" class="stx-mono text-2xl font-bold text-white mt-0.5">7,000 MB/s</div>
                            <div class="text-[11px] text-gray-500 mt-0.5 font-mono">PCIe 4.0 NVMe</div>
                        </div>
                    </div>

                    <!-- Instant Local Currency & Rails Note -->
                    <div class="p-3 rounded-[2px] bg-[#090D15] border border-[#1D263B] text-xs text-gray-400 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <i class="ti ti-currency-rupee text-cyan-optic"></i>
                            <span>Zero foreign transaction fees</span>
                        </span>
                        <span class="font-mono text-white text-xs">Direct LKR Billed</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Infrastructure Hardware Benchmarks -->
<section class="border-b border-[#1D263B] bg-[#0A0D15] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-left">
            <div class="border-l-2 border-cyan-optic pl-4">
                <div class="stx-label text-[11px]">Processor Architecture</div>
                <div class="text-white font-bold text-base mt-0.5">Ryzen 9 & i9-14900K</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">High single-thread boost</div>
            </div>
            <div class="border-l-2 border-[#2A3754] pl-4">
                <div class="stx-label text-[11px]">Storage Throughput</div>
                <div class="text-white font-bold text-base mt-0.5">Gen4 Enterprise NVMe</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">Instant chunk & map loads</div>
            </div>
            <div class="border-l-2 border-[#2A3754] pl-4">
                <div class="stx-label text-[11px]">DDoS Protection</div>
                <div class="text-white font-bold text-base mt-0.5">1.2 Tbps L3/L4 Filter</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">Always-on edge scrubbers</div>
            </div>
            <div class="border-l-2 border-emerald-500 pl-4">
                <div class="stx-label text-[11px]">Network Reliability</div>
                <div class="text-white font-bold text-base mt-0.5">99.98% Service SLA</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">Automated node failover</div>
            </div>
        </div>
    </div>
</section>

<!-- Hosting Categories Catalog -->
<section id="categories" class="py-20 border-b border-[#1D263B]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="stx-label mb-2 text-cyan-optic">Dedicated Infrastructure</div>
                <h2 class="stx-display text-3xl sm:text-4xl text-white">Choose Your Hosting Tier</h2>
            </div>
            <p class="text-sm text-gray-400 max-w-md">
                Every server instance includes full root web console, SFTP access, automated backups, and dedicated Colombo NOC support.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <div class="stx-card p-6 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-[2px] bg-[#0A0D15] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-2xl group-hover:border-cyan-optic transition">
                            <i class="ti {{ $category->icon ?? 'ti-server-2' }}"></i>
                        </div>
                        <span class="stx-badge bg-[#111624] text-gray-400 border border-[#1D263B]">
                            {{ $category->products->count() }} {{ \Illuminate\Support\Str::plural('package', $category->products->count()) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-white group-hover:text-cyan-optic transition">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                            {{ $category->description }}
                        </p>
                    </div>

                    <!-- Technical Feature Highlights -->
                    <div class="pt-2 flex flex-wrap gap-1.5 text-[11px] font-mono">
                        <span class="px-2 py-0.5 rounded-[2px] bg-[#0A0D15] text-gray-400 border border-[#1D263B]">NVMe Gen4</span>
                        <span class="px-2 py-0.5 rounded-[2px] bg-[#0A0D15] text-gray-400 border border-[#1D263B]">DDoS Shield</span>
                        <span class="px-2 py-0.5 rounded-[2px] bg-[#0A0D15] text-gray-400 border border-[#1D263B]">SFTP & MySQL</span>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-[#1D263B] flex items-center justify-between">
                    <div>
                        <span class="stx-label text-[10px] block">Starting from</span>
                        <span class="stx-mono text-lg font-bold text-white">
                            Rs. {{ number_format($category->products->flatMap->plans->min('price_monthly') ?? 1500, 0) }}
                            <span class="text-xs text-gray-500 font-normal">/ mo</span>
                        </span>
                    </div>

                    <a href="{{ route('hosting.storefront.category', $category->slug) }}" class="stx-btn-outline !py-2 !px-3.5 text-xs font-semibold">
                        Configure <i class="ti ti-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Game Server Configurations -->
<section class="py-20 border-b border-[#1D263B] bg-[#080B10]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="stx-label mb-2 text-cyan-optic">Deploy Ready Nodes</div>
                <h2 class="stx-display text-3xl sm:text-4xl text-white">Popular Server Configurations</h2>
            </div>
            <a href="{{ route('hosting.storefront.games') }}" class="text-xs font-mono text-cyan-optic hover:underline flex items-center gap-1">
                View all game servers <i class="ti ti-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredPlans as $plan)
            <div class="stx-card p-6 flex flex-col justify-between {{ $loop->iteration === 2 || $plan->is_featured ? 'border-cyan-optic/60 bg-[#0F1420]' : '' }}">
                <div class="space-y-4">
                    <!-- Badge and Title -->
                    <div class="flex items-center justify-between">
                        <span class="stx-badge bg-[#111624] text-cyan-optic border border-cyan-optic/30">
                            {{ $plan->product->name ?? 'Dedicated Node' }}
                        </span>
                        @if($plan->display_badge)
                            <span class="stx-badge bg-cyan-optic text-[#041014] font-bold">
                                {{ $plan->display_badge }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                        @if($plan->tagline)
                            <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $plan->tagline }}</p>
                        @endif
                    </div>

                    <!-- Price Block -->
                    <div class="p-3.5 rounded-[2px] bg-[#07090E] border border-[#1D263B]">
                        <div class="stx-mono text-2xl font-bold text-white">
                            Rs. {{ number_format((float) $plan->price_lkr, 0) }}
                        </div>
                        <div class="text-[11px] font-mono text-gray-500 mt-0.5">
                            LKR / {{ strtolower($plan->billing_period->getLabel()) }} • No setup fees
                        </div>
                    </div>

                    <!-- Hardware Spec Grid -->
                    <div class="grid grid-cols-2 gap-2 text-xs font-mono pt-1">
                        <div class="p-2 rounded-[2px] bg-[#0A0D15] border border-[#161F30]">
                            <span class="text-gray-500 block text-[10px]">RAM</span>
                            <span class="text-white font-semibold">{{ $plan->memory_gb }} GB DDR5</span>
                        </div>
                        <div class="p-2 rounded-[2px] bg-[#0A0D15] border border-[#161F30]">
                            <span class="text-gray-500 block text-[10px]">CPU</span>
                            <span class="text-white font-semibold">{{ $plan->cpu_percent }}% Ryzen 9</span>
                        </div>
                        <div class="p-2 rounded-[2px] bg-[#0A0D15] border border-[#161F30]">
                            <span class="text-gray-500 block text-[10px]">NVMe Disk</span>
                            <span class="text-white font-semibold">{{ $plan->disk_gb }} GB Gen4</span>
                        </div>
                        <div class="p-2 rounded-[2px] bg-[#0A0D15] border border-[#161F30]">
                            <span class="text-gray-500 block text-[10px]">Database</span>
                            <span class="text-white font-semibold">MySQL MariaDB</span>
                        </div>
                    </div>

                    <!-- Feature Checks -->
                    <ul class="space-y-1.5 text-xs text-gray-300 pt-2">
                        @foreach(array_slice($plan->display_features, 0, 4) as $feature)
                            <li class="flex items-center gap-2">
                                <i class="ti ti-check text-cyan-optic text-xs shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="pt-6 mt-6 border-t border-[#1D263B]">
                    <a href="{{ route('hosting.checkout.show', $plan->slug) }}" class="w-full stx-btn-cyan text-xs !py-3">
                        Deploy Server Now <i class="ti ti-bolt text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Sri Lanka ISP Peering Matrix -->
<section class="py-20 border-b border-[#1D263B] bg-[#07090E]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-5 space-y-4">
                <div class="stx-label text-cyan-optic">Network Topology</div>
                <h2 class="stx-display text-3xl sm:text-4xl text-white">Direct Sri Lanka ISP Peering</h2>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Most international hosts route traffic from Sri Lanka through Singapore, London, or Mumbai, causing 90ms–180ms latency. STX Enterprises peers directly at the Colombo Internet Exchange (IX) for direct, hop-minimized routes across all local carriers.
                </p>
                <div class="pt-2 flex items-center gap-3 text-xs font-mono text-gray-400">
                    <span class="flex items-center gap-1.5 text-emerald-400"><span class="stx-dot bg-emerald-400"></span> BGP IPv4/IPv6 Dual Stack</span>
                    <span>•</span>
                    <span>10G Direct Uplinks</span>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="stx-panel p-6 border-[#243047] overflow-x-auto">
                    <table class="w-full text-left font-mono text-xs">
                        <thead>
                            <tr class="border-b border-[#1D263B] text-gray-500">
                                <th class="pb-3 font-medium">Internet Service Provider</th>
                                <th class="pb-3 font-medium">Connection Type</th>
                                <th class="pb-3 font-medium text-right">Avg Colombo Ping</th>
                                <th class="pb-3 font-medium text-right">Routing Path</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1D263B]">
                            <tr class="stx-row">
                                <td class="py-3 font-bold text-white flex items-center gap-2">
                                    <span class="stx-dot bg-cyan-optic"></span> SLT-Mobitel Fibre
                                </td>
                                <td class="py-3 text-gray-400">FTTH Fiber Optics</td>
                                <td class="py-3 text-right text-emerald-400 font-bold">~ 8 ms</td>
                                <td class="py-3 text-right text-gray-400">Direct IX (0 Hops)</td>
                            </tr>
                            <tr class="stx-row">
                                <td class="py-3 font-bold text-white flex items-center gap-2">
                                    <span class="stx-dot bg-cyan-optic"></span> Dialog Broadband
                                </td>
                                <td class="py-3 text-gray-400">Fibre & Home 4G</td>
                                <td class="py-3 text-right text-emerald-400 font-bold">~ 11 ms</td>
                                <td class="py-3 text-right text-gray-400">Direct Peering</td>
                            </tr>
                            <tr class="stx-row">
                                <td class="py-3 font-bold text-white flex items-center gap-2">
                                    <span class="stx-dot bg-cyan-optic"></span> Airtel Lanka
                                </td>
                                <td class="py-3 text-gray-400">4G / LTE Core</td>
                                <td class="py-3 text-right text-emerald-400 font-bold">~ 16 ms</td>
                                <td class="py-3 text-right text-gray-400">Colombo BGP</td>
                            </tr>
                            <tr class="stx-row">
                                <td class="py-3 font-bold text-white flex items-center gap-2">
                                    <span class="stx-dot bg-cyan-optic"></span> Hutchison Telecom
                                </td>
                                <td class="py-3 text-gray-400">4G Mobile Broadband</td>
                                <td class="py-3 text-right text-emerald-400 font-bold">~ 18 ms</td>
                                <td class="py-3 text-right text-gray-400">Colombo BGP</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Native Sri Lanka Payment Rails & Auto Verification -->
<section id="payment-methods" class="py-20 border-b border-[#1D263B] bg-[#0A0D15]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="stx-label mb-2 text-cyan-optic">Frictionless Checkout</div>
                <h2 class="stx-display text-3xl sm:text-4xl text-white">Native Sri Lankan Payment Rails</h2>
            </div>
            <p class="text-sm text-gray-400 max-w-md">
                No foreign currency exchange markups or international card declines. Pay in Sri Lankan Rupees through any domestic bank or mobile wallet.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Bank Transfer Card -->
            <div class="stx-card p-6 space-y-3">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-building-bank"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Direct Domestic Bank Transfer</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Transfer instantly from Commercial Bank, BOC, Sampath Vishwa, HNB, or Peoples Bank. Attach your receipt or screenshot on the tracking page for rapid confirmation.
                </p>
                <div class="text-[11px] font-mono text-emerald-400 pt-1 flex items-center gap-1.5">
                    <i class="ti ti-circle-check"></i> Verified in &lt; 15 mins by NOC
                </div>
            </div>

            <!-- eZ Cash Card -->
            <div class="stx-card p-6 space-y-3">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-red-400 flex items-center justify-center text-xl">
                    <i class="ti ti-device-mobile"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Dialog eZ Cash Mobile Rails</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Pay using your Dialog mobile wallet via #111# or at any islandwide retail merchant. Submit your 10-digit transaction ID during checkout for instant matching.
                </p>
                <div class="text-[11px] font-mono text-emerald-400 pt-1 flex items-center gap-1.5">
                    <i class="ti ti-circle-check"></i> Available 24/7 Islandwide
                </div>
            </div>

            <!-- HelaPay LANKAQR Card -->
            <div class="stx-card p-6 space-y-3">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-amber-400 flex items-center justify-center text-xl">
                    <i class="ti ti-qrcode"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Helakuru HelaPay LANKAQR</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Scan and pay directly using any Central Bank of Sri Lanka LANKAQR enabled mobile banking app. Clean audit trails and verified transaction references.
                </p>
                <div class="text-[11px] font-mono text-emerald-400 pt-1 flex items-center gap-1.5">
                    <i class="ti ti-circle-check"></i> Central Bank of Sri Lanka Standard
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script for Interactive Node Latency Switcher -->
<script>
    function switchNode(node) {
        const btnCol = document.getElementById('btn-colombo');
        const btnSin = document.getElementById('btn-singapore');
        const ping = document.getElementById('stat-ping');
        const pingSub = document.getElementById('stat-ping-sub');
        const cpu = document.getElementById('stat-cpu');
        const io = document.getElementById('stat-io');

        if (node === 'colombo') {
            btnCol.className = 'p-3 text-left rounded-[2px] border border-cyan-optic bg-cyan-optic/10 text-white font-mono text-xs transition';
            btnSin.className = 'p-3 text-left rounded-[2px] border border-[#1D263B] bg-[#0A0E17] text-gray-400 font-mono text-xs hover:border-gray-500 transition';
            ping.innerText = '< 12 ms';
            pingSub.innerText = 'Colombo Metro Loop';
            cpu.innerText = '5.7 GHz';
            io.innerText = '7,000 MB/s';
        } else {
            btnSin.className = 'p-3 text-left rounded-[2px] border border-cyan-optic bg-cyan-optic/10 text-white font-mono text-xs transition';
            btnCol.className = 'p-3 text-left rounded-[2px] border border-[#1D263B] bg-[#0A0E17] text-gray-400 font-mono text-xs hover:border-gray-500 transition';
            ping.innerText = '32 - 38 ms';
            pingSub.innerText = 'Equinix SG1 Direct';
            cpu.innerText = '5.4 GHz';
            io.innerText = '6,800 MB/s';
        }
    }
</script>
@endsection