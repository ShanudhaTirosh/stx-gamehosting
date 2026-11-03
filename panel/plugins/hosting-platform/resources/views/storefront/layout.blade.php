<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    @php
        $seo = \BrandedHosting\Platform\Models\HostingSeoSetting::current();
        $pageTitle = $__env->yieldContent('title', '');
        $renderedTitle = $seo->renderTitle($pageTitle);
        $canonicalUrl = $__env->yieldContent('canonical', url()->current());
        $metaDescription = $__env->yieldContent('meta_description', $seo->meta_description);
        $metaKeywords = $__env->yieldContent('meta_keywords', $seo->meta_keywords);
        $ogImage = $__env->yieldContent('og_image', url($seo->og_image ?: '/images/plans/default-server.svg'));
        $ogType = $__env->yieldContent('og_type', 'website');
        $robotsPolicy = $__env->yieldContent('meta_robots', $seo->robots_policy ?: 'index, follow');
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $renderedTitle }}</title>

    <!-- Search Engine & Crawling Metadata -->
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $robotsPolicy }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- OpenGraph (Facebook, Discord, WhatsApp, LinkedIn) -->
    <meta property="og:site_name" content="{{ $seo->site_name }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle ?: $seo->site_name }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter / X Cards -->
    <meta name="twitter:card" content="summary_large_image">
    @if($seo->twitter_handle)
        <meta name="twitter:site" content="{{ $seo->twitter_handle }}">
        <meta name="twitter:creator" content="{{ $seo->twitter_handle }}">
    @endif
    <meta name="twitter:title" content="{{ $pageTitle ?: $seo->site_name }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Search Console Webmaster Verification -->
    @if(!empty($seo->google_site_verification))
        <meta name="google-site-verification" content="{{ $seo->google_site_verification }}">
    @endif
    @if(!empty($seo->bing_site_verification))
        <meta name="msvalidate.01" content="{{ $seo->bing_site_verification }}">
    @endif

    <link rel="icon" type="image/x-icon" href="{{ config('app.favicon', '/pelican.ico') }}">

    <!-- Structured Data (JSON-LD) -->
    @if($seo->schema_org_enabled)
        @php
            $orgSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $seo->site_name,
                'legalName' => $seo->company_legal_name,
                'url' => url('/'),
                'logo' => url('/pelican.svg'),
                'description' => $seo->meta_description,
                'telephone' => $seo->contact_phone,
                'email' => $seo->contact_email,
                'priceRange' => $seo->schema_price_range,
            ];
            if (!empty($seo->support_discord_url)) {
                $orgSchema['sameAs'] = [$seo->support_discord_url];
            }
            $websiteSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $seo->site_name,
                'url' => url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/help') . '?search={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        <script type="application/ld+json">
        {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    @yield('extra_schema')

    <!-- Custom Head Scripts (Admin Configured) -->
    @if(!empty($seo->custom_head_scripts))
        {!! $seo->custom_head_scripts !!}
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        stx: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        obsidian: '#080B11',
                        surface: '#0E131F',
                        surfaceLight: '#141C2E',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body { background-color: #080B11; color: #F3F4F6; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; overflow-x: hidden; }
        .glass-panel { background: rgba(14, 19, 31, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-card { background: rgba(20, 28, 46, 0.5); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.06); }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-2px); }
        .glass-nav { background: rgba(8, 11, 17, 0.88); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
        .text-gradient { background: linear-gradient(135deg, #FFFFFF 0%, #A5B4FC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .text-accent-gradient { background: linear-gradient(135deg, #818CF8 0%, #C084FC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="flex flex-col min-h-screen selection:bg-indigo-500 selection:text-white">
    <!-- Top Global Notification Bar -->
    <div class="bg-indigo-950/40 border-b border-indigo-500/20 text-xs text-gray-300 py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> SRI LANKA FAST ROUTING
                </span>
                <span>Official Billed Currency: <strong class="text-white">Sri Lankan Rupee (LKR)</strong></span>
            </div>
            <div class="flex items-center gap-4 text-gray-400 text-xs">
                <a href="{{ route('hosting.status.index') }}" class="hover:text-emerald-400 transition flex items-center gap-1.5">
        .stx-input { width:100%; padding:.7rem .85rem; background:var(--bg); border:1px solid var(--line2); border-radius:2px; color:var(--text); font-size:.95rem; }
        .stx-input:focus { outline:none; border-color:var(--acc); }
        .stx-input::placeholder { color:#5C626C; }
        .stx-field > label { display:block; margin-bottom:.4rem; }
        .stx-hint { font-size:.8rem; color:var(--mut); margin-top:.35rem; }
        .stx-choice { display:flex; gap:.75rem; padding:.9rem 1rem; border:1px solid var(--line2); border-radius:2px; cursor:pointer; transition:border-color .15s, background .15s; }
        .stx-choice:hover { border-color: var(--mut); }
        .stx-choice:has(input:checked) { border-color: var(--acc); background: rgba(212,255,58,.05); }
        .stx-choice input { accent-color: var(--acc); margin-top:.3rem; }
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Live Telemetry: 99.98% SLA
                </a>
                <span>•</span>
                <a href="{{ route('hosting.storefront.contact') }}" class="hover:text-white transition flex items-center gap-1">
                    <i class="ti ti-headset text-indigo-400"></i> Colombo NOC Desk
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo & Name -->
                <a href="{{ route('hosting.storefront.index') }}" class="flex items-center gap-3 group shrink-0">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-xl shadow-indigo-600/30 group-hover:scale-105 transition duration-300">
                        <i class="ti ti-shield-bolt text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-black tracking-tight text-white flex items-center gap-1.5">
                            STX <span class="text-indigo-400 font-extrabold">ENTERPRISES</span>
                        </div>
                        <div class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold -mt-1">Cloud & Game Hosting</div>
                    </div>
                </a>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('hosting.storefront.index') }}" class="text-sm font-semibold {{ request()->routeIs('hosting.storefront.index') ? 'text-indigo-400 font-bold' : 'text-gray-300 hover:text-white' }} transition">Home</a>
                    <a href="{{ route('hosting.storefront.games') }}" class="text-sm font-semibold {{ request()->routeIs('hosting.storefront.games') ? 'text-indigo-400 font-bold' : 'text-gray-300 hover:text-white' }} transition">Game Servers</a>
                    <a href="{{ route('hosting.storefront.category', 'minecraft') }}" class="text-sm font-semibold {{ request()->is('hosting/minecraft*') ? 'text-indigo-400 font-bold' : 'text-gray-300 hover:text-white' }} transition">Minecraft</a>
                    <a href="{{ route('hosting.kb.index') }}" class="text-sm font-semibold {{ request()->is('help*') || request()->is('knowledgebase*') ? 'text-indigo-400 font-bold' : 'text-gray-300 hover:text-white' }} transition flex items-center gap-1">
                        <i class="ti ti-help text-indigo-400"></i> Help Center
                    </a>
                    <a href="{{ route('hosting.storefront.about') }}" class="text-sm font-semibold {{ request()->routeIs('hosting.storefront.about') ? 'text-indigo-400 font-bold' : 'text-gray-300 hover:text-white' }} transition">About</a>
                    <a href="{{ route('hosting.storefront.contact') }}" class="text-sm font-semibold {{ request()->routeIs('hosting.storefront.contact') ? 'text-indigo-400 font-bold' : 'text-gray-300 hover:text-white' }} transition">Contact</a>
                    <a href="{{ route('hosting.status.index') }}" class="text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1.5">
                        <i class="ti ti-activity-heartbeat"></i> Status
                    </a>
                </nav>

                <!-- Action / Authentication Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isRootAdmin() ? url('/admin') : url(config('panel.client_path', 'app')) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-500 hover:to-purple-500 transition shadow-lg shadow-indigo-600/30">
                            <i class="ti ti-layout-dashboard"></i> {{ auth()->user()->isRootAdmin() ? 'Admin Portal' : 'My Dashboard' }}
                        </a>
                    @else
                        <a href="{{ route('filament.app.auth.login') }}" class="hidden sm:inline-flex px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-white transition">
                            Sign In
                        </a>
                        <a href="{{ route('hosting.storefront.games') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-500 hover:to-purple-500 transition shadow-lg shadow-indigo-600/30">
                            Deploy Server
                        </a>
                    @endauth

                    <!-- Mobile Hamburger Toggle -->
                    <button
                        type="button"
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                        class="lg:hidden p-2.5 rounded-xl bg-white/5 border border-white/10 text-gray-300 hover:text-white"
                        aria-label="Toggle Navigation"
                    >
                        <i class="ti ti-menu-2 text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobile-menu" class="hidden lg:hidden py-4 border-t border-white/10 space-y-2">
                <a href="{{ route('hosting.storefront.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white">Home</a>
                <a href="{{ route('hosting.storefront.games') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white">Game Servers</a>
                <a href="{{ route('hosting.storefront.category', 'minecraft') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white">Minecraft Java/Bedrock</a>
                <a href="{{ route('hosting.storefront.category', 'fivem') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white">FiveM GTA V</a>
                <a href="{{ route('hosting.storefront.about') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white">About Us</a>
                <a href="{{ route('hosting.storefront.contact') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white">Contact NOC Desk</a>
                <a href="{{ route('hosting.status.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-emerald-400 hover:bg-white/5">Cluster Status & Probes</a>
            </div>
        </div>
    </header>

    <!-- Main Content Injection -->
    <main class="flex-grow relative">
        @yield('content')
    </main>

    <!-- Structured Modern Footer -->
    <footer class="bg-[#05070B] border-t border-white/10 pt-16 pb-12 mt-24 relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -bottom-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-white/10">
                <!-- Company Column -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg">
                            <i class="ti ti-shield-bolt text-xl"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tight text-white">STX <span class="text-indigo-400">ENTERPRISES</span></span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                        High-performance containerized game server and cloud hosting engineered for ultra-low latency, NVMe speeds, and direct Sri Lankan payment rails.
                    </p>
                    <div class="pt-2 text-xs text-gray-400 space-y-1.5">
                        <p class="flex items-center gap-2"><i class="ti ti-map-pin text-indigo-400"></i> Galle Road, Colombo 03, Western Province, Sri Lanka</p>
                        <p class="flex items-center gap-2"><i class="ti ti-phone text-indigo-400"></i> +94 11 234 5678 (24/7 Priority Hotline)</p>
                        <p class="flex items-center gap-2"><i class="ti ti-mail text-indigo-400"></i> billing@stxenterprises.lk</p>
                    </div>
                </div>

                <!-- Game Servers -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Game Servers</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('hosting.storefront.category', 'minecraft') }}" class="hover:text-white transition">Minecraft (Paper / Fabric)</a></li>
                        <li><a href="{{ route('hosting.storefront.category', 'hytale') }}" class="hover:text-white transition">Hytale Dedicated</a></li>
                        <li><a href="{{ route('hosting.storefront.category', 'fivem') }}" class="hover:text-white transition">FiveM GTA V</a></li>
                        <li><a href="{{ route('hosting.storefront.category', 'mta') }}" class="hover:text-white transition">MTA: San Andreas</a></li>
                        <li><a href="{{ route('hosting.storefront.games') }}" class="hover:text-white transition text-indigo-400 font-semibold">View All Game Servers →</a></li>
                    </ul>
                </div>

                <!-- Company & Support -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Company & NOC</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('hosting.storefront.about') }}" class="hover:text-white transition">About STX Enterprises</a></li>
                        <li><a href="{{ route('hosting.kb.index') }}" class="hover:text-white transition flex items-center gap-1.5">
                            <i class="ti ti-help text-indigo-400"></i> Knowledge Base & Help
                        </a></li>
                        <li><a href="{{ route('hosting.storefront.contact') }}" class="hover:text-white transition">Contact Us & Helpdesk</a></li>
                        <li><a href="{{ route('hosting.status.index') }}" class="hover:text-white transition flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Live Telemetry Status
                        </a></li>
                        <li><a href="{{ url(trim(config('panel.client_path', 'app') . '/customer-orders', '/')) }}" class="hover:text-white transition">My orders</a></li>
                        <li><a href="{{ url('/admin') }}" class="hover:text-white transition">Staff Admin Login</a></li>
                    </ul>
                </div>

                <!-- Local Payment Rails -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Accepted Payment Rails</h4>
                    <div class="space-y-2 text-xs text-gray-400">
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-white/5 border border-white/5">
                            <i class="ti ti-building-bank text-indigo-400 text-base"></i>
                            <span>Commercial Bank / BOC / Sampath</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-white/5 border border-white/5">
                            <i class="ti ti-device-mobile text-red-400 text-base"></i>
                            <span>Dialog eZ Cash (#111#)</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-white/5 border border-white/5">
                            <i class="ti ti-brand-cashapp text-yellow-400 text-base"></i>
                            <span>Helakuru HelaPay LANKAQR</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Legal Compliance Links -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <p>&copy; {{ date('Y') }} {{ $seo->company_legal_name ?: 'STX Enterprises Lanka (Pvt) Ltd' }}. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('hosting.storefront.about') }}" class="hover:text-gray-400 transition">About</a>
                    <a href="{{ route('hosting.storefront.contact') }}" class="hover:text-gray-400 transition">Contact</a>
                    <a href="{{ route('hosting.status.index') }}" class="hover:text-gray-400 transition">Status</a>
                    <a href="{{ route('hosting.seo.sitemap') }}" class="hover:text-gray-400 transition">Sitemap</a>
                    <a href="{{ route('hosting.storefront.terms') }}" class="hover:text-gray-400 transition">Terms of Service</a>
                    <a href="{{ route('hosting.storefront.privacy') }}" class="hover:text-gray-400 transition">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    @if(!empty($seo->custom_body_scripts))
        {!! $seo->custom_body_scripts !!}
    @endif
</body>
</html>
