@extends('hosting-platform::storefront.layout')

@section('title', 'Enterprise Game & Cloud Server Hosting - Sri Lanka & APAC')
@section('meta_description', 'Deploy ultra-fast Minecraft, FiveM, Hytale, and cloud servers in Sri Lanka & Singapore. Instant auto-provisioning with 99.98% SLA and local payment rails.')
@section('meta_keywords', 'game server hosting, minecraft server sri lanka, fivem hosting colombo, cloud vps sri lanka, low latency game hosting, stx enterprises')

@section('content')
<!-- Hero Section with Glassmorphism & Mesh Background -->
<section class="relative overflow-hidden pt-16 pb-24 md:pt-24 md:pb-36">
    <!-- Ambient Radial Light Elements -->
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[450px] bg-indigo-600/15 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <!-- Live Status Capsule -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-white/5 border border-white/10 text-gray-300 mb-8 backdrop-blur-xl shadow-lg shadow-black/20">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span class="text-white font-bold">STX Cloud Engine</span> • Gen4 NVMe SSDs • DDoS Shield Active
        </div>

        <!-- Main Title -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-[1.1] max-w-5xl mx-auto">
            High-Performance Server Hosting <br>
            <span class="text-accent-gradient">Engineered Without Compromise</span>
        </h1>

        <p class="mt-6 text-base sm:text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
            Deploy low-latency Minecraft, Hytale, FiveM, and Bot instances in seconds. Powered by STX Enterprises infrastructure with native Sri Lankan payment rails (Bank, eZ Cash, HelaPay).
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="#categories" class="px-8 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-xl shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                Explore Hosting Plans
            </a>
            <a href="{{ route('hosting.status.index') }}" class="px-8 py-4 rounded-2xl text-base font-bold text-gray-300 glass-card hover:text-white transition">
                <i class="ti ti-activity-heartbeat text-emerald-400 mr-2"></i> View Live Node Status
            </a>
        </div>

        <!-- Key Infrastructure Metrics (Glass Cards) -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 max-w-4xl mx-auto">
            <div class="glass-card p-6 rounded-3xl text-left">
                <div class="text-3xl font-black text-white font-mono">99.98%</div>
                <div class="text-xs text-indigo-400 font-semibold mt-1">Guaranteed SLA</div>
                <div class="text-[11px] text-gray-500 mt-1">High-availability failover</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-left">
                <div class="text-3xl font-black text-emerald-400 font-mono">&lt; 15ms</div>
                <div class="text-xs text-emerald-400 font-semibold mt-1">Sri Lanka Ping</div>
                <div class="text-[11px] text-gray-500 mt-1">Direct ISP low-hop routing</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-left">
                <div class="text-3xl font-black text-purple-400 font-mono">5.7 GHz</div>
                <div class="text-xs text-purple-400 font-semibold mt-1">Ryzen 9 & i9 CPUs</div>
                <div class="text-[11px] text-gray-500 mt-1">Maximum single-core clock</div>
            </div>
            <div class="glass-card p-6 rounded-3xl text-left">
                <div class="text-3xl font-black text-indigo-400 font-mono">Instant</div>
                <div class="text-xs text-indigo-400 font-semibold mt-1">Auto-Provisioning</div>
                <div class="text-[11px] text-gray-500 mt-1">Container ready upon approval</div>
            </div>
        </div>
    </div>
</section>

<!-- Service Categories Catalog -->
<section id="categories" class="py-20 border-t border-white/10 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Production Catalog</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">Choose Your Hosting Category</h2>
            <p class="text-sm text-gray-400">
                All plans include automated backups, root file manager, full web console, subdomain masking, and live diagnostics.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
            <div class="glass-card rounded-3xl p-8 flex flex-col justify-between transition duration-300 relative group overflow-hidden">
                <div class="space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-3xl group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
                        <i class="ti {{ $category->icon ?? 'ti-server-2' }}"></i>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-400 mt-2 leading-relaxed">
                            {{ $category->description }}
                        </p>
                    </div>

                    <!-- Feature Tags -->
                    <div class="pt-2 flex flex-wrap gap-2 text-xs">
                        <span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-300 border border-white/5">NVMe Storage</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-300 border border-white/5">Full Console</span>
                        <span class="px-2.5 py-1 rounded-lg bg-white/5 text-gray-300 border border-white/5">DDoS Filter</span>
                    </div>
                </div>

                <div class="pt-8 mt-6 border-t border-white/10 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-gray-400 uppercase tracking-wider block">Pricing From</span>
                        <span class="text-xl font-black font-mono text-white">
                            Rs. {{ number_format($category->products->flatMap->plans->min('price_monthly') ?? 1500, 0) }} <span class="text-xs text-gray-400 font-normal">/mo</span>
                        </span>
                    </div>

                    <a href="{{ route('hosting.storefront.category', $category->slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 transition shadow-lg shadow-indigo-600/20">
                        View Plans <i class="ti ti-chevron-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Game Server Plans Comparison -->
<section class="py-20 border-t border-white/10 bg-white/[0.01]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Popular Servers</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">Featured Hosting Packages</h2>
            <p class="text-sm text-gray-400">High-demand configurations ready for immediate deployment.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredPlans as $plan)
            <div class="glass-card rounded-3xl p-7 flex flex-col justify-between border group relative overflow-hidden {{ $loop->iteration === 2 || $plan->is_featured ? 'border-indigo-500/60 shadow-2xl shadow-indigo-500/15 ring-1 ring-indigo-500/30' : 'border-white/10' }}">
                <!-- Badge Ribbon -->
                <div class="absolute top-4 right-4 z-20">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase {{ $loop->iteration === 2 ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md' : 'bg-white/10 text-gray-300 border border-white/10' }}">
                        {{ $plan->display_badge }}
                    </span>
                </div>

                <div class="space-y-5">
                    <!-- Artwork Thumbnail -->
                    <div class="w-full h-36 rounded-2xl overflow-hidden bg-black/40 border border-white/5 relative group-hover:border-indigo-500/30 transition">
                        <img src="{{ $plan->display_image }}" alt="{{ $plan->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-500/30">
                                {{ $plan->product->name ?? 'Game Node' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-white">{{ $plan->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $plan->tagline }}</p>
                    </div>

                    <!-- Pricing Banner -->
                    <div class="p-4 rounded-2xl bg-black/40 border border-white/5">
                        <span class="text-3xl font-black font-mono text-white">Rs. {{ number_format((float) $plan->price_lkr, 0) }}</span>
                        <span class="text-xs text-gray-400 font-semibold block mt-0.5">LKR per {{ $plan->billing_period->getLabel() }}</span>
                    </div>

                    <!-- Hardware Spec Breakdown -->
                    <ul class="space-y-2.5 text-xs text-gray-300 border-t border-white/5 pt-4">
                        @foreach(array_slice($plan->display_features, 0, 5) as $feature)
                            <li class="flex items-center gap-2">
                                <i class="ti ti-circle-check text-emerald-400 text-sm shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="pt-6 mt-6 border-t border-white/10">
                    <a href="{{ route('hosting.checkout.show', $plan->slug) }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm text-white bg-indigo-600 hover:bg-indigo-500 transition shadow-lg shadow-indigo-600/30 group-hover:scale-[1.02]">
                        Deploy Instance <i class="ti ti-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Local Sri Lankan Payment Verification Rails -->
<section id="payment-methods" class="py-20 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Native Sri Lanka Rails</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">Frictionless Manual Payment Verification</h2>
            <p class="text-sm text-gray-400">
                Pay in Sri Lankan Rupees through any local banking app, mobile wallet, or counter deposit. No international credit card required.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Bank Transfer Card -->
            <div class="glass-card rounded-3xl p-8 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-2xl border border-indigo-500/20">
                    <i class="ti ti-building-bank"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Direct Bank Transfer</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Transfer instantly from Commercial Bank, Sampath Vishwa, BOC, HNB, or Peoples Bank. Simply upload your slip photo or mobile PDF for prompt verification.
                </p>
                <div class="text-xs font-semibold text-emerald-400 flex items-center gap-1.5 pt-2">
                    <i class="ti ti-clock-check"></i> Verification: Under 15 Minutes
                </div>
            </div>

            <!-- eZ Cash Card -->
            <div class="glass-card rounded-3xl p-8 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-400 flex items-center justify-center text-2xl border border-red-500/20">
                    <i class="ti ti-device-mobile"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Dialog eZ Cash</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Pay from your mobile phone using your eZ Cash mobile wallet or visit any nearby merchant islandwide. Enter your transaction ID during checkout.
                </p>
                <div class="text-xs font-semibold text-emerald-400 flex items-center gap-1.5 pt-2">
                    <i class="ti ti-clock-check"></i> Verification: Under 15 Minutes
                </div>
            </div>

            <!-- HelaPay Card -->
            <div class="glass-card rounded-3xl p-8 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-yellow-500/10 text-yellow-400 flex items-center justify-center text-2xl border border-yellow-500/20">
                    <i class="ti ti-brand-cashapp"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Helakuru HelaPay</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Seamless payments powered by HelaPay direct account debits. Clean, verified records with instant receipt confirmation.
                </p>
                <div class="text-xs font-semibold text-emerald-400 flex items-center gap-1.5 pt-2">
                    <i class="ti ti-clock-check"></i> Verification: Under 15 Minutes
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
