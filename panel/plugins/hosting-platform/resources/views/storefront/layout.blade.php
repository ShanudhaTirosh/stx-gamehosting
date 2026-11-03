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

    <!-- Authentic Brand Typography (Google Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (with bespoke STX Design System tokens) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['"Archivo Black"', '"Space Grotesk"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    },
                    colors: {
                        canvas: '#07090E',
                        surface: {
                            DEFAULT: '#0E131E',
                            elevated: '#141B2B',
                            high: '#1A2338',
                        },
                        line: {
                            DEFAULT: '#1D263B',
                            bright: '#2A3754',
                        },
                        cyan: {
                            optic: '#00D2ED',
                            dim: '#0B4D58',
                        },
                        indigo: {
                            brand: '#6366F1',
                            deep: '#4338CA',
                        },
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        :root {
            --bg: #07090E;
            --surface: #0E131E;
            --surface-elevated: #141B2B;
            --surface-high: #1A2338;
            --line: #1D263B;
            --line2: #2A3754;
            --text: #F8FAFC;
            --mut: #94A3B8;
            --mut-dark: #64748B;
            --acc: #6366F1;
            --cyan: #00D2ED;
            --ok: #10B981;
            --warn: #F59E0B;
            --bad: #EF4444;
        }

        body {
            background-color: var(--bg);
            color: #E2E8F0;
            font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .stx-display { font-family: 'Archivo Black', 'Space Grotesk', sans-serif; letter-spacing: -0.02em; }
        .stx-mono { font-family: 'JetBrains Mono', monospace; }
        .stx-panel { background: var(--surface); border: 1px solid var(--line); border-radius: 3px; }
        .stx-card { background: var(--surface); border: 1px solid var(--line); border-radius: 3px; transition: border-color .15s ease, background-color .15s ease; }
        .stx-card:hover { border-color: var(--cyan); background-color: var(--surface-elevated); }
        .stx-row:hover { background-color: rgba(255, 255, 255, 0.02); }

        .stx-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            background: var(--acc);
            color: #FFFFFF;
            border-radius: 3px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: background-color .15s ease;
        }
        .stx-btn:hover { background: #4F46E5; }

        .stx-btn-cyan {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            background: var(--cyan);
            color: #041014;
            border-radius: 3px;
            transition: opacity .15s ease;
        }
        .stx-btn-cyan:hover { opacity: 0.92; }

        .stx-btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            background: transparent;
            color: #E2E8F0;
            border: 1px solid var(--line2);
            border-radius: 3px;
            transition: border-color .15s ease, color .15s ease;
        }
        .stx-btn-outline:hover { border-color: var(--cyan); color: #FFFFFF; }

        .stx-rule { border-color: var(--line); }
        .stx-label { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--mut); }
        .stx-badge { display: inline-flex; align-items: center; gap: 0.35rem; font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 2px; }
        .stx-dot { display: inline-block; width: 7px; height: 7px; border-radius: 9999px; }

        .stx-input { width: 100%; padding: 0.65rem 0.85rem; background: #0A0D15; border: 1px solid var(--line2); border-radius: 3px; color: #FFFFFF; font-size: 0.875rem; }
        .stx-input:focus { outline: none; border-color: var(--cyan); }
        .stx-input::placeholder { color: #526077; }
        .stx-field > label { display: block; margin-bottom: 0.35rem; }
        .stx-hint { font-size: 0.8rem; color: var(--mut); margin-top: 0.35rem; }

        .stx-choice { display: flex; gap: 0.75rem; padding: 0.85rem 1rem; border: 1px solid var(--line); border-radius: 3px; cursor: pointer; transition: border-color .15s, background .15s; }
        .stx-choice:hover { border-color: var(--line2); }
        .stx-choice:has(input:checked) { border-color: var(--cyan); background: rgba(0, 210, 237, 0.06); }
        .stx-choice input { accent-color: var(--cyan); margin-top: 0.25rem; }
    </style>
</head>
<body class="flex flex-col min-h-screen selection:bg-[#00D2ED] selection:text-[#041014]">
    <!-- Top Telecommunications & SLA Ticker Bar -->
    <div class="bg-[#0A0E17] border-b border-[#1A2234] text-xs text-gray-400 py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <span class="stx-badge bg-emerald-950/40 text-emerald-300 border border-emerald-500/30">
                    <span class="stx-dot bg-emerald-400"></span> COLOMBO IX ACTIVE
                </span>
                <span class="text-[13px]">Billed Currency: <strong class="text-white font-mono">Sri Lankan Rupee (LKR)</strong></span>
            </div>
            <div class="flex items-center gap-4 text-xs font-mono">
                <a href="{{ route('hosting.status.index') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5 text-emerald-400">
                    <span class="stx-dot bg-emerald-400"></span> 99.98% Network SLA
                </a>
                <span class="text-gray-600">|</span>
                <a href="{{ route('hosting.storefront.contact') }}" class="hover:text-white transition flex items-center gap-1 text-gray-400">
                    <i class="ti ti-headset text-indigo-400"></i> NOC Desk (+94 11 234 5678)
                </a>
            </div>
        </div>
    </div>

    <!-- Main Precision Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#07090E]/95 backdrop-blur-md border-b border-[#1D263B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Identity -->
                <a href="{{ route('hosting.storefront.index') }}" class="flex items-center gap-3.5 group shrink-0">
                    <div class="w-10 h-10 rounded-[3px] bg-[#0E1420] border border-[#2A3754] flex items-center justify-center text-cyan-optic group-hover:border-cyan-optic transition">
                        <i class="ti ti-server-2 text-xl"></i>
                    </div>
                    <div>
                        <div class="stx-display text-xl tracking-tight text-white flex items-center gap-1.5">
                            STX <span class="text-cyan-optic">ENTERPRISES</span>
                        </div>
                        <div class="text-[10px] uppercase font-mono tracking-widest text-gray-400 -mt-0.5">Cloud & Game Infra</div>
                    </div>
                </a>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden lg:flex items-center gap-7">
                    <a href="{{ route('hosting.storefront.index') }}" class="text-sm font-medium transition pb-1 {{ request()->routeIs('hosting.storefront.index') ? 'text-white border-b-2 border-cyan-optic' : 'text-gray-400 hover:text-white' }}">
                        Home
                    </a>
                    <a href="{{ route('hosting.storefront.games') }}" class="text-sm font-medium transition pb-1 {{ request()->routeIs('hosting.storefront.games') ? 'text-white border-b-2 border-cyan-optic' : 'text-gray-400 hover:text-white' }}">
                        Game Servers
                    </a>
                    <a href="{{ route('hosting.storefront.category', 'minecraft') }}" class="text-sm font-medium transition pb-1 {{ request()->is('hosting/minecraft*') ? 'text-white border-b-2 border-cyan-optic' : 'text-gray-400 hover:text-white' }}">
                        Minecraft
                    </a>
                    <a href="{{ route('hosting.kb.index') }}" class="text-sm font-medium transition pb-1 {{ request()->is('help*') || request()->is('knowledgebase*') ? 'text-white border-b-2 border-cyan-optic' : 'text-gray-400 hover:text-white' }} flex items-center gap-1.5">
                        <i class="ti ti-help text-indigo-400"></i> Help Center
                    </a>
                    <a href="{{ route('hosting.storefront.about') }}" class="text-sm font-medium transition pb-1 {{ request()->routeIs('hosting.storefront.about') ? 'text-white border-b-2 border-cyan-optic' : 'text-gray-400 hover:text-white' }}">
                        About
                    </a>
                    <a href="{{ route('hosting.storefront.contact') }}" class="text-sm font-medium transition pb-1 {{ request()->routeIs('hosting.storefront.contact') ? 'text-white border-b-2 border-cyan-optic' : 'text-gray-400 hover:text-white' }}">
                        Contact
                    </a>
                    <a href="{{ route('hosting.status.index') }}" class="text-sm font-mono text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1.5">
                        <span class="stx-dot bg-emerald-400"></span> Status
                    </a>
                </nav>

                <!-- Action & User Portal Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isRootAdmin() ? url('/admin') : url(config('panel.client_path', 'app')) }}" class="stx-btn !bg-[#151C2A] !text-white border border-[#2D3A52] hover:border-cyan-optic">
                            <i class="ti ti-layout-dashboard text-cyan-optic"></i> {{ auth()->user()->isRootAdmin() ? 'Admin Portal' : 'My Dashboard' }}
                        </a>
                    @else
                        <a href="{{ route('filament.app.auth.login') }}" class="hidden sm:inline-flex text-sm font-medium text-gray-300 hover:text-white transition px-3 py-2">
                            Sign In
                        </a>
                        <a href="{{ route('hosting.storefront.games') }}" class="stx-btn-cyan">
                            <i class="ti ti-bolt"></i> Deploy Server
                        </a>
                    @endauth

                    <!-- Mobile Drawer Hamburger -->
                    <button
                        type="button"
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                        class="lg:hidden p-2 rounded-[3px] bg-[#111622] border border-[#212B3E] text-gray-300 hover:text-white"
                        aria-label="Toggle Navigation"
                    >
                        <i class="ti ti-menu-2 text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobile-menu" class="hidden lg:hidden py-4 border-t border-[#1D263B] space-y-1">
                <a href="{{ route('hosting.storefront.index') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">Home</a>
                <a href="{{ route('hosting.storefront.games') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">Game Servers Catalog</a>
                <a href="{{ route('hosting.storefront.category', 'minecraft') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">Minecraft Java & Bedrock</a>
                <a href="{{ route('hosting.storefront.category', 'fivem') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">FiveM GTA V</a>
                <a href="{{ route('hosting.kb.index') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">Help Center & Docs</a>
                <a href="{{ route('hosting.storefront.about') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">About STX Enterprises</a>
                <a href="{{ route('hosting.storefront.contact') }}" class="block px-3 py-2 rounded-[3px] text-sm font-medium text-gray-300 hover:bg-[#141B2B] hover:text-white">Contact NOC Desk</a>
                <a href="{{ route('hosting.status.index') }}" class="block px-3 py-2 rounded-[3px] text-sm font-mono text-emerald-400 hover:bg-[#141B2B]">Telemetry & Probes</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow relative">
        @yield('content')
    </main>

    <!-- Structured Technical Footer -->
    <footer class="bg-[#06080D] border-t border-[#1D263B] pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-[#1D263B]">
                <!-- Brand & Infrastructure Details -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-[3px] bg-[#0E1420] border border-[#2A3754] flex items-center justify-center text-cyan-optic">
                            <i class="ti ti-server-2 text-lg"></i>
                        </div>
                        <span class="stx-display text-xl text-white">STX <span class="text-cyan-optic">ENTERPRISES</span></span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                        High-frequency bare-metal compute and containerized game infrastructure with direct Colombo BGP peering, NVMe Gen4 storage, and native Sri Lankan payment rails.
                    </p>
                    <div class="pt-2 text-xs font-mono text-gray-400 space-y-1.5">
                        <p class="flex items-center gap-2"><i class="ti ti-map-pin text-cyan-optic"></i> Galle Road, Colombo 03, Western Province, Sri Lanka</p>
                        <p class="flex items-center gap-2"><i class="ti ti-phone text-cyan-optic"></i> +94 11 234 5678 (24/7 NOC Dispatch)</p>
                        <p class="flex items-center gap-2"><i class="ti ti-mail text-cyan-optic"></i> noc@stxenterprises.lk</p>
                    </div>
                </div>

                <!-- Server Portfolio -->
                <div class="space-y-3">
                    <div class="stx-label text-white">Server Portfolio</div>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('hosting.storefront.category', 'minecraft') }}" class="hover:text-white transition">Minecraft Paper & Forge</a></li>
                        <li><a href="{{ route('hosting.storefront.category', 'fivem') }}" class="hover:text-white transition">FiveM GTA V Servers</a></li>
                        <li><a href="{{ route('hosting.storefront.category', 'hytale') }}" class="hover:text-white transition">Hytale Dedicated</a></li>
                        <li><a href="{{ route('hosting.storefront.category', 'mta') }}" class="hover:text-white transition">MTA: San Andreas</a></li>
                        <li><a href="{{ route('hosting.storefront.games') }}" class="hover:text-cyan-optic transition font-medium text-xs flex items-center gap-1">Browse all servers <i class="ti ti-arrow-right"></i></a></li>
                    </ul>
                </div>

                <!-- Technical NOC & Help -->
                <div class="space-y-3">
                    <div class="stx-label text-white">Operations & Help</div>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('hosting.storefront.about') }}" class="hover:text-white transition">Datacenter & Peering</a></li>
                        <li><a href="{{ route('hosting.kb.index') }}" class="hover:text-white transition">Knowledge Base & Guides</a></li>
                        <li><a href="{{ route('hosting.storefront.contact') }}" class="hover:text-white transition">NOC Helpdesk Dispatch</a></li>
                        <li><a href="{{ route('hosting.status.index') }}" class="hover:text-emerald-400 transition font-mono text-xs flex items-center gap-1.5">
                            <span class="stx-dot bg-emerald-400"></span> Live Telemetry Status
                        </a></li>
                        <li><a href="{{ url(trim(config('panel.client_path', 'app') . '/customer-orders', '/')) }}" class="hover:text-white transition">My Orders & Invoices</a></li>
                    </ul>
                </div>

                <!-- Sri Lanka Payment Rails -->
                <div class="space-y-3">
                    <div class="stx-label text-white">Accepted Local Rails</div>
                    <div class="space-y-2 text-xs">
                        <div class="p-2.5 rounded-[3px] bg-[#0E131E] border border-[#1D263B] text-gray-300 flex items-center gap-2">
                            <i class="ti ti-building-bank text-cyan-optic text-sm"></i>
                            <span>Commercial / BOC / Sampath Bank</span>
                        </div>
                        <div class="p-2.5 rounded-[3px] bg-[#0E131E] border border-[#1D263B] text-gray-300 flex items-center gap-2">
                            <i class="ti ti-device-mobile text-red-400 text-sm"></i>
                            <span>Dialog eZ Cash (#111#)</span>
                        </div>
                        <div class="p-2.5 rounded-[3px] bg-[#0E131E] border border-[#1D263B] text-gray-300 flex items-center gap-2">
                            <i class="ti ti-qrcode text-amber-400 text-sm"></i>
                            <span>Helakuru HelaPay LANKAQR</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright & Compliance -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4 font-mono">
                <p>&copy; {{ date('Y') }} {{ $seo->company_legal_name ?: 'STX Enterprises Lanka (Pvt) Ltd' }}. Registered in Sri Lanka.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('hosting.storefront.about') }}" class="hover:text-gray-300 transition">About</a>
                    <a href="{{ route('hosting.storefront.contact') }}" class="hover:text-gray-300 transition">Contact</a>
                    <a href="{{ route('hosting.status.index') }}" class="hover:text-gray-300 transition">Status</a>
                    <a href="{{ route('hosting.seo.sitemap') }}" class="hover:text-gray-300 transition">Sitemap</a>
                    <a href="{{ route('hosting.storefront.terms') }}" class="hover:text-gray-300 transition">Terms</a>
                    <a href="{{ route('hosting.storefront.privacy') }}" class="hover:text-gray-300 transition">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    @if(!empty($seo->custom_body_scripts))
        {!! $seo->custom_body_scripts !!}
    @endif
</body>
</html>