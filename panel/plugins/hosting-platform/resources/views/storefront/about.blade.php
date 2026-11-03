@extends('hosting-platform::storefront.layout')

@section('title', 'Datacenter Architecture & Network Infrastructure — STX Enterprises')
@section('meta_description', 'Learn about STX Enterprises: high-frequency game servers, sub-15ms Sri Lanka network routing, Colombo DC & Singapore Equinix nodes, and our enterprise SLA commitment.')

@section('content')
<!-- Hero Section -->
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span class="text-cyan-optic">About & Architecture</span>
        </nav>

        <h1 class="stx-display text-4xl sm:text-6xl text-white tracking-tight">
            Sri Lanka’s High-Frequency Cloud Infrastructure
        </h1>
        <p class="mt-4 text-base sm:text-lg text-gray-400 max-w-3xl leading-relaxed">
            STX Enterprises was founded to eliminate excessive overseas routing latency, volatile currency exchange premiums, and overseas support delays for South Asian gaming communities and software developers.
        </p>

        <div class="mt-8 flex flex-wrap items-center gap-4">
            <a href="{{ route('hosting.storefront.games') }}" class="stx-btn-cyan text-xs !px-5 !py-3">
                <i class="ti ti-server-2"></i> Deploy Server
            </a>
            <a href="{{ route('hosting.status.index') }}" class="stx-btn-outline text-xs !px-5 !py-3 font-mono">
                <span class="stx-dot bg-emerald-400"></span> Live Telemetry Status
            </a>
        </div>
    </div>
</section>

<!-- Hardware & SLA Telemetry Strip -->
<section class="border-b border-[#1D263B] bg-[#0A0D15] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-left">
            <div class="border-l-2 border-cyan-optic pl-4">
                <div class="stx-label text-[11px]">Nationwide Latency</div>
                <div class="stx-mono text-white font-bold text-xl mt-0.5">&lt; 12 ms</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">Direct Colombo IX peering</div>
            </div>
            <div class="border-l-2 border-[#2A3754] pl-4">
                <div class="stx-label text-[11px]">Processor Clocks</div>
                <div class="stx-mono text-white font-bold text-xl mt-0.5">5.7 GHz</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">AMD Ryzen 9 7950X Zen 4</div>
            </div>
            <div class="border-l-2 border-emerald-500 pl-4">
                <div class="stx-label text-[11px]">Verified SLA</div>
                <div class="stx-mono text-emerald-400 font-bold text-xl mt-0.5">99.98%</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">Dual-grid UPS & generator</div>
            </div>
            <div class="border-l-2 border-[#2A3754] pl-4">
                <div class="stx-label text-[11px]">Edge Scrubbing</div>
                <div class="stx-mono text-white font-bold text-xl mt-0.5">1.2 Tbps</div>
                <div class="text-xs text-gray-500 font-mono mt-0.5">Hardware L3/L4/L7 filter</div>
            </div>
        </div>
    </div>
</section>

<!-- Datacenter Facilities -->
<section class="py-20 border-b border-[#1D263B] bg-[#080B10]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <div class="stx-label text-cyan-optic mb-2">Datacenter Locations</div>
            <h2 class="stx-display text-3xl sm:text-4xl text-white">Tier-III Datacenter Presence</h2>
            <p class="text-sm text-gray-400 mt-2 max-w-xl">
                Strategically deployed bare-metal nodes engineered for maximum single-core game performance and reliable edge connectivity.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Facility 1: Colombo Primary DC -->
            <div class="stx-card p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-[#1D263B] pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="ti ti-building-broadcast-tower text-cyan-optic"></i>
                            <span>Colombo NOC & Core Datacenter</span>
                        </h3>
                        <div class="text-xs font-mono text-gray-500 mt-0.5">Colombo 03, Western Province, Sri Lanka</div>
                    </div>
                    <span class="stx-badge bg-emerald-950/40 text-emerald-300 border border-emerald-500/30">
                        <span class="stx-dot bg-emerald-400"></span> Primary Core
                    </span>
                </div>

                <p class="text-xs text-gray-400 leading-relaxed">
                    Direct local fiber peering with SLT-Mobitel Fibre, Dialog Axiata, Airtel Lanka, and Hutchison. Delivers sub-12ms nationwide ping for gaming clans and local enterprise apps without passing through submarine cables.
                </p>

                <div class="grid grid-cols-2 gap-3 text-xs font-mono pt-2">
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">Compute Fleet</span>
                        <strong class="text-white">AMD Ryzen 9 7950X Zen 4</strong>
                    </div>
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">Storage Tier</span>
                        <strong class="text-white">Gen4 NVMe RAID-10 Arrays</strong>
                    </div>
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">DDoS Protection</span>
                        <strong class="text-white">Corero SmartWall + Voxility</strong>
                    </div>
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">Power Infrastructure</span>
                        <strong class="text-white">2N UPS + Cummins Generator</strong>
                    </div>
                </div>
            </div>

            <!-- Facility 2: Singapore Equinix SG1 -->
            <div class="stx-card p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-[#1D263B] pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="ti ti-network text-cyan-optic"></i>
                            <span>Singapore Equinix SG1 Hub</span>
                        </h3>
                        <div class="text-xs font-mono text-gray-500 mt-0.5">Jurong East, Singapore (APAC Hub)</div>
                    </div>
                    <span class="stx-badge bg-[#0E1420] text-cyan-optic border border-cyan-optic/30">
                        <span class="stx-dot bg-cyan-optic"></span> APAC Transit
                    </span>
                </div>

                <p class="text-xs text-gray-400 leading-relaxed">
                    Equinix SG1 interconnection facility linking Southeast Asia, India, and Australia. Designed for international game leagues, Discord bot clusters, and multi-region microservices requiring global low-latency backbones.
                </p>

                <div class="grid grid-cols-2 gap-3 text-xs font-mono pt-2">
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">Compute Fleet</span>
                        <strong class="text-white">AMD EPYC 9654 Genoa</strong>
                    </div>
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">Storage Tier</span>
                        <strong class="text-white">Enterprise NVMe U.2 / U.3</strong>
                    </div>
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">DDoS Protection</span>
                        <strong class="text-white">Arbor Sightline Peak Edge</strong>
                    </div>
                    <div class="p-3 bg-[#07090E] border border-[#182133] rounded-[2px]">
                        <span class="text-gray-500 block text-[10px]">Transit Uplink</span>
                        <strong class="text-white">100 Gbps Tier-1 Redundant</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Operating Principles -->
<section class="py-20 border-b border-[#1D263B] bg-[#07090E]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <div class="stx-label text-cyan-optic mb-2">Engineering Standards</div>
            <h2 class="stx-display text-3xl sm:text-4xl text-white">Why Sri Lankan Gamers Choose STX</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stx-card p-6 space-y-3">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-coin"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Direct Sri Lankan Rupee Pricing</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Zero foreign transaction markups, zero cross-border card declines, and no volatile USD fluctuations. Pay fixed LKR through Commercial Bank, BOC, Sampath Bank, Dialog eZ Cash, or HelaPay LANKAQR.
                </p>
            </div>

            <div class="stx-card p-6 space-y-3">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-bolt"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Instant Container Allocation</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Automated Wings daemon provisioning assigns isolated memory and CPU cores with instant SFTP credentials, MySQL databases, and game port forwarding in under 60 seconds upon order verification.
                </p>
            </div>

            <div class="stx-card p-6 space-y-3">
                <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl">
                    <i class="ti ti-headset"></i>
                </div>
                <h3 class="text-lg font-bold text-white">24/7 Colombo NOC Support</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Talk directly to experienced server systems engineers based in Colombo via phone, WhatsApp, or customer ticket. Average initial dispatch response is under 15 minutes.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-[#080B10]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="stx-panel p-10 space-y-4 border-[#243047] bg-[#0C1019]">
            <h2 class="stx-display text-3xl font-bold text-white">Ready for Low-Latency Gaming?</h2>
            <p class="text-xs sm:text-sm text-gray-400 max-w-xl mx-auto leading-relaxed">
                Deploy your server instance today with instant version changers, plugin managers, and 24/7 Colombo technical support.
            </p>
            <div class="pt-4 flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('hosting.storefront.games') }}" class="stx-btn-cyan text-xs !px-6 !py-3">
                    <i class="ti ti-bolt"></i> Deploy Server Now
                </a>
                <a href="{{ route('hosting.storefront.contact') }}" class="stx-btn-outline text-xs !px-6 !py-3">
                    Contact NOC Team
                </a>
            </div>
        </div>
    </div>
</section>
@endsection