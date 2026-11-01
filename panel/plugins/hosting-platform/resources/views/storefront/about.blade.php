@extends('hosting-platform::storefront.layout')

@section('title', 'About Us — STX Enterprises | Next-Gen Cloud & Game Infrastructure')
@section('meta_description', 'Learn about STX Enterprises: high-performance game servers, sub-15ms Sri Lanka network routing, Colombo DC & Singapore Equinix nodes, and our enterprise SLA commitment.')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-20 overflow-hidden">
    <!-- Ambient Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-gradient-to-tr from-indigo-600/20 via-purple-600/15 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/25 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>ENTERPRISE CLOUD ARCHITECTURE</span>
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
            Engineering Sri Lanka’s <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-cyan-300">
                Lowest-Latency Cloud & Game Infrastructure
            </span>
        </h1>
        <p class="mt-6 text-base sm:text-lg text-gray-400 max-w-3xl mx-auto leading-relaxed">
            STX Enterprises was founded to eliminate lag, exorbitant foreign-currency charges, and slow support for South Asian gamers and developers. We build high-frequency bare-metal compute nodes with direct peering across Dialog, SLT-Mobitel, Airtel, and Hutch.
        </p>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('hosting.storefront.games') }}" class="px-8 py-3.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition shadow-xl shadow-indigo-600/25 flex items-center gap-2">
                <i class="ti ti-server-2 text-lg"></i>
                <span>Explore Game Servers</span>
            </a>
            <a href="{{ route('hosting.status.index') }}" class="px-8 py-3.5 rounded-xl text-sm font-bold text-emerald-300 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 transition flex items-center gap-2">
                <i class="ti ti-activity-heartbeat text-lg text-emerald-400"></i>
                <span>Live Cluster Status</span>
            </a>
        </div>
    </div>
</section>

<!-- Core Value Metrics -->
<section class="py-12 border-y border-white/5 bg-white/[0.01]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="text-3xl sm:text-4xl font-black text-white font-mono">&lt; 8 ms</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-indigo-400 mt-1">Nationwide SL Latency</div>
                <p class="text-[11px] text-gray-400 mt-1">Direct Colombo BGP IX peering</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="text-3xl sm:text-4xl font-black text-white font-mono">5.7 GHz</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-purple-400 mt-1">AMD Ryzen 9 7950X</div>
                <p class="text-[11px] text-gray-400 mt-1">Single-thread peak boost clocks</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="text-3xl sm:text-4xl font-black text-white font-mono">99.98%</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-emerald-400 mt-1">Verified SLA Uptime</div>
                <p class="text-[11px] text-gray-400 mt-1">Redundant upstream power & transit</p>
            </div>
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5">
                <div class="text-3xl sm:text-4xl font-black text-white font-mono">1.2 Tbps</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-cyan-400 mt-1">Anycast DDoS Mitigation</div>
                <p class="text-[11px] text-gray-400 mt-1">Always-on Layer 3/4 & 7 filtering</p>
            </div>
        </div>
    </div>
</section>

<!-- Datacenter Locations -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <h2 class="text-3xl font-black text-white">Tier-III Global Datacenter Presence</h2>
            <p class="text-sm text-gray-400 mt-2">Strategically positioned edge infrastructure engineered for maximum uptime and minimum jitter.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Datacenter 1: Colombo -->
            <div class="p-8 rounded-3xl bg-surface border border-white/10 relative overflow-hidden group hover:border-indigo-500/40 transition duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-2xl">
                            <i class="ti ti-building-broadcast-tower"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Colombo Primary DC</h3>
                            <p class="text-xs text-gray-400">Western Province, Sri Lanka</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold text-emerald-300 bg-emerald-500/15 border border-emerald-500/30 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Active Core
                    </span>
                </div>

                <p class="text-sm text-gray-300 leading-relaxed">
                    Direct local fiber termination with SLT-Mobitel, Dialog Axiata, Airtel Lanka, and Hutch. Provides sub-10ms nationwide ping for Minecraft, FiveM, and gaming clans without going through overseas undersea transit cables.
                </p>

                <div class="mt-6 pt-6 border-t border-white/10 grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 block">Hardware Fleet:</span>
                        <strong class="text-white">AMD Ryzen 9 7950X Zen 4</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Storage:</span>
                        <strong class="text-white">PCIe 4.0 NVMe RAID-10</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">DDoS Shield:</span>
                        <strong class="text-white">Corero SmartWall + Voxility</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Power:</span>
                        <strong class="text-white">2N UPS + Cummins Generator</strong>
                    </div>
                </div>
            </div>

            <!-- Datacenter 2: Singapore -->
            <div class="p-8 rounded-3xl bg-surface border border-white/10 relative overflow-hidden group hover:border-purple-500/40 transition duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-purple-400 text-2xl">
                            <i class="ti ti-network"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Singapore Equinix SG1</h3>
                            <p class="text-xs text-gray-400">Jurong, Singapore (APAC)</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold text-cyan-300 bg-cyan-500/15 border border-cyan-500/30 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        APAC Transit
                    </span>
                </div>

                <p class="text-sm text-gray-300 leading-relaxed">
                    Equinix SG1 interconnection hub linking Southeast Asia, India, and Australia. Ideal for international game networks, Discord bots, and cross-border web APIs needing ultra-fast global backbone throughput.
                </p>

                <div class="mt-6 pt-6 border-t border-white/10 grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 block">Hardware Fleet:</span>
                        <strong class="text-white">AMD EPYC 9654 Genoa</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Storage:</span>
                        <strong class="text-white">Enterprise NVMe U.2 / U.3</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">DDoS Shield:</span>
                        <strong class="text-white">Arbor Sightline Peak Filtering</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Backbone:</span>
                        <strong class="text-white">100 Gbps Redundant Upstream</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose STX Enterprises -->
<section class="py-20 border-t border-white/5 bg-[#05070B]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl font-black text-white">Why Sri Lankan Gamers & Developers Choose STX</h2>
            <p class="text-sm text-gray-400 mt-2">Built by engineers who understand the frustrations of overseas hosts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-surface border border-white/5 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center text-xl">
                    <i class="ti ti-coin"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Direct LKR Currency Pricing</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Say goodbye to unpredictable foreign exchange markups, 3.5% cross-border credit card transaction fees, and bank card blockages. Pay in fixed Sri Lankan Rupees through Commercial Bank, Sampath Bank, BOC, HNB, eZ Cash, or HelaPay.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-surface border border-white/5 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-xl">
                    <i class="ti ti-bolt"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Instant Container Provisioning</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Our platform automatically allocates Docker containers on isolated Wings daemons. Upon payment verification, your server, dedicated port, and SFTP access are deployed in under 60 seconds without manual ticketing delays.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-surface border border-white/5 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/15 text-indigo-400 flex items-center justify-center text-xl">
                    <i class="ti ti-shield-check"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Automated Daily Backups</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Every game server includes automated snapshot storage isolated from the host node. Roll back server worlds, plugin configs, or corrupt databases with a single click from the web console.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 relative">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="p-10 sm:p-14 rounded-3xl bg-gradient-to-b from-indigo-950/60 to-surface border border-indigo-500/20 shadow-2xl relative overflow-hidden">
            <h2 class="text-3xl sm:text-4xl font-black text-white">Ready for Lag-Free Sri Lankan Gaming?</h2>
            <p class="mt-4 text-sm text-gray-300 max-w-xl mx-auto">
                Deploy your server today with instant version switcher, Paper/Fabric/Forge support, and 24/7 dedicated local assistance.
            </p>
            <div class="mt-8 flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('hosting.storefront.games') }}" class="px-8 py-3.5 rounded-xl font-bold text-sm text-white bg-indigo-600 hover:bg-indigo-500 transition shadow-lg shadow-indigo-600/30">
                    Deploy Server Now
                </a>
                <a href="{{ route('hosting.storefront.contact') }}" class="px-8 py-3.5 rounded-xl font-bold text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 transition border border-white/10">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
