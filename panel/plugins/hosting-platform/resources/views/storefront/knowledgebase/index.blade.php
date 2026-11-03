@extends('hosting-platform::storefront.layout')

@php
    $seo = \BrandedHosting\Platform\Models\HostingSeoSetting::current();
@endphp

@section('title', 'Knowledge Base & Technical Help Center — STX Enterprises')

@section('content')
<!-- Header & Search Section -->
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span class="text-cyan-optic">Help Center</span>
        </nav>

        <div class="max-w-3xl">
            <h1 class="stx-display text-4xl sm:text-5xl text-white tracking-tight">
                Knowledge Base & Documentation
            </h1>
            <p class="mt-3 text-base text-gray-400 leading-relaxed">
                Comprehensive step-by-step technical guides covering server configuration, SFTP file access, MySQL database credentials, automated backup vaults, and port forwarding.
            </p>

            <!-- Search Input -->
            <form action="{{ route('hosting.kb.index') }}" method="GET" class="mt-6 relative max-w-xl">
                <div class="relative flex items-center">
                    <i class="ti ti-search absolute left-3.5 text-gray-500 text-sm pointer-events-none"></i>
                    <input
                        type="text"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Search docs: SFTP, MySQL, Backups, Java 21, Wings..."
                        class="stx-input !pl-10 !pr-28 !py-3 !text-xs !bg-[#0C1019] !border-[#212B3E]"
                    >
                    <button
                        type="submit"
                        class="absolute right-1.5 px-4 py-2 rounded-[2px] bg-cyan-optic text-[#041014] text-xs font-mono font-bold hover:opacity-90 transition"
                    >
                        Search
                    </button>
                </div>
            </form>

            <!-- Quick Search Tags -->
            <div class="flex flex-wrap items-center gap-2 pt-3 text-xs font-mono text-gray-500">
                <span>Quick lookups:</span>
                <a href="{{ route('hosting.kb.index', ['q' => 'sftp']) }}" class="px-2 py-0.5 rounded-[2px] bg-[#0E1420] text-gray-400 hover:text-white border border-[#1D263B] transition">SFTP FileZilla</a>
                <a href="{{ route('hosting.kb.index', ['q' => 'database']) }}" class="px-2 py-0.5 rounded-[2px] bg-[#0E1420] text-gray-400 hover:text-white border border-[#1D263B] transition">MySQL Database</a>
                <a href="{{ route('hosting.kb.index', ['q' => 'backup']) }}" class="px-2 py-0.5 rounded-[2px] bg-[#0E1420] text-gray-400 hover:text-white border border-[#1D263B] transition">Backups & Restore</a>
                <a href="{{ route('hosting.kb.index', ['q' => 'java']) }}" class="px-2 py-0.5 rounded-[2px] bg-[#0E1420] text-gray-400 hover:text-white border border-[#1D263B] transition">Java 21 Selector</a>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-16 bg-[#080B10]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Search Results (If Active) -->
        @if(!empty($query))
            <div class="mb-14">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#1D263B]">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="ti ti-list-search text-cyan-optic"></i>
                        <span>Search Results for <strong class="text-cyan-optic font-mono">"{{ $query }}"</strong></span>
                    </h2>
                    <a href="{{ route('hosting.kb.index') }}" class="text-xs font-mono text-gray-400 hover:text-white transition">Clear Search &times;</a>
                </div>

                @if(count($searchResults) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($searchResults as $res)
                            <a href="{{ route('hosting.kb.article', [$res['category_slug'], $res['article_slug']]) }}" class="p-5 stx-card group block">
                                <div class="flex items-center gap-2 text-[11px] font-mono text-cyan-optic mb-1.5">
                                    <i class="{{ $res['category_icon'] }}"></i>
                                    <span>{{ $res['category_name'] }}</span>
                                    <span class="text-gray-600">|</span>
                                    <span class="text-gray-500">{{ $res['read_time'] }}</span>
                                </div>
                                <h3 class="text-base font-bold text-white group-hover:text-cyan-optic transition">
                                    {{ $res['title'] }}
                                </h3>
                                <p class="text-xs text-gray-400 leading-relaxed mt-1">
                                    {{ $res['summary'] }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 p-8 stx-panel max-w-md mx-auto">
                        <i class="ti ti-search-off text-3xl text-gray-500 mb-2 block"></i>
                        <h3 class="text-sm font-bold text-white mb-1">No guides found matching your query</h3>
                        <p class="text-xs text-gray-400 mb-4">
                            Try searching with broader terms or contact our Colombo NOC helpdesk directly.
                        </p>
                        <a href="{{ route('hosting.storefront.contact') }}" class="stx-btn-cyan text-xs !py-2 !px-4">
                            <i class="ti ti-headset"></i> Contact NOC Helpdesk
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Knowledge Modules Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-[#1D263B] pb-4">
                <div>
                    <div class="stx-label text-cyan-optic mb-1">Documentation Directory</div>
                    <h2 class="stx-display text-2xl text-white">Technical Guides & Reference</h2>
                </div>
                <span class="stx-badge bg-[#0E1420] text-gray-400 border border-[#1D263B]">
                    {{ count($categories) }} Knowledge Modules
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $catSlug => $category)
                    <div class="stx-card p-6 flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="w-10 h-10 rounded-[2px] bg-[#07090E] border border-[#212B3E] text-cyan-optic flex items-center justify-center text-xl group-hover:border-cyan-optic transition">
                                <i class="{{ $category['icon'] }}"></i>
                            </div>

                            <h3 class="text-lg font-bold text-white group-hover:text-cyan-optic transition">
                                {{ $category['name'] }}
                            </h3>
                            <p class="text-xs text-gray-400 leading-relaxed">
                                {{ $category['description'] }}
                            </p>

                            <!-- Article Links -->
                            <div class="pt-3 border-t border-[#1D263B] space-y-1.5 font-mono text-xs">
                                @foreach($category['articles'] as $artSlug => $art)
                                    <a href="{{ route('hosting.kb.article', [$catSlug, $artSlug]) }}" class="group/item flex items-center justify-between text-gray-400 hover:text-white transition py-0.5">
                                        <span class="truncate pr-2">• {{ $art['title'] }}</span>
                                        <i class="ti ti-chevron-right text-gray-600 group-hover/item:text-cyan-optic transition text-[10px]"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-[#1D263B] flex items-center justify-between text-xs font-mono">
                            <span class="text-gray-500">{{ count($category['articles']) }} {{ Str::plural('article', count($category['articles'])) }}</span>
                            <a href="{{ route('hosting.kb.category', $catSlug) }}" class="text-cyan-optic hover:underline flex items-center gap-1 font-semibold">
                                Browse Module <i class="ti ti-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Helpdesk Dispatch Strip -->
        <div class="mt-16 stx-panel p-8 border-[#243047] bg-[#0C1019] flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="text-lg font-bold text-white">Have a question not covered in the docs?</h3>
                <p class="text-xs text-gray-400 max-w-xl">
                    Our Colombo systems engineers provide free world migrations, modpack setup assistance, and custom port configuration.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hosting.storefront.contact') }}" class="stx-btn-cyan text-xs !py-2.5 !px-4">
                    <i class="ti ti-headset"></i> Contact NOC Desk
                </a>
                <a href="{{ $seo->support_discord_url ?: config('app.discord_url', 'https://discord.gg/shanutechx') }}" target="_blank" class="stx-btn-outline text-xs !py-2.5 !px-4">
                    <i class="ti ti-brand-discord"></i> Discord Community
                </a>
            </div>
        </div>

    </div>
</section>
@endsection