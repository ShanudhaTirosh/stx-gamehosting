@extends('hosting-platform::storefront.layout')

@section('title', 'Knowledge Base & Customer Help Center — STX Enterprises')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Hero Search Section -->
    <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold">
            <i class="ti ti-help"></i> Official STX Customer Help Desk
        </div>
        <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-white">
            How can we <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">help you today?</span>
        </h1>
        <p class="text-gray-400 text-base leading-relaxed">
            Search our comprehensive guides covering every single button, control, and setting in your client dashboard.
        </p>

        <!-- Search Input -->
        <form action="{{ route('hosting.kb.index') }}" method="GET" class="relative max-w-2xl mx-auto mt-6">
            <div class="relative flex items-center">
                <i class="ti ti-search absolute left-4 text-gray-400 text-xl pointer-events-none"></i>
                <input
                    type="text"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search for Start, Stop, SFTP, MySQL, Backups, Java versions..."
                    class="w-full pl-12 pr-28 py-4 rounded-2xl bg-white/5 border border-white/10 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 shadow-2xl transition"
                >
                <button
                    type="submit"
                    class="absolute right-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold transition shadow-lg"
                >
                    Search
                </button>
            </div>
        </form>

        <!-- Quick Search Tags -->
        <div class="flex flex-wrap items-center justify-center gap-2 pt-2 text-xs text-gray-400">
            <span>Popular:</span>
            <a href="{{ route('hosting.kb.index', ['q' => 'sftp']) }}" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 transition">SFTP FileZilla</a>
            <a href="{{ route('hosting.kb.index', ['q' => 'power']) }}" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 transition">Start / Stop / Kill</a>
            <a href="{{ route('hosting.kb.index', ['q' => 'database']) }}" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 transition">MariaDB Credentials</a>
            <a href="{{ route('hosting.kb.index', ['q' => 'backup']) }}" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 transition">Cloud Vault Backups</a>
            <a href="{{ route('hosting.kb.index', ['q' => 'java']) }}" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 transition">Java 21 Selector</a>
        </div>
    </div>

    <!-- Search Results View (If query active) -->
    @if(!empty($query))
        <div class="mb-16">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="ti ti-list-search text-indigo-400"></i>
                    Search Results for <span class="text-indigo-400">"{{ $query }}"</span>
                </h2>
                <a href="{{ route('hosting.kb.index') }}" class="text-xs text-gray-400 hover:text-white transition">Clear Search &times;</a>
            </div>

            @if(count($searchResults) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($searchResults as $res)
                        <a href="{{ route('hosting.kb.article', [$res['category_slug'], $res['article_slug']]) }}" class="p-5 rounded-2xl bg-white/[0.03] hover:bg-white/[0.07] border border-white/10 hover:border-indigo-500/40 transition group block">
                            <div class="flex items-center gap-2 text-xs font-semibold text-indigo-400 mb-2">
                                <i class="{{ $res['category_icon'] }}"></i>
                                <span>{{ $res['category_name'] }}</span>
                                <span class="text-gray-600">&bull;</span>
                                <span class="text-gray-500">{{ $res['read_time'] }}</span>
                            </div>
                            <h3 class="text-base font-bold text-white group-hover:text-indigo-300 transition mb-1">
                                {{ $res['title'] }}
                            </h3>
                            <p class="text-xs text-gray-400 leading-relaxed">
                                {{ $res['summary'] }}
                            </p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 p-8 rounded-2xl bg-white/[0.02] border border-white/10">
                    <i class="ti ti-mood-empty text-4xl text-gray-500 mb-3 block"></i>
                    <h3 class="text-base font-bold text-white mb-1">No articles found matching your query</h3>
                    <p class="text-xs text-gray-400 max-w-md mx-auto mb-4">
                        Try searching with fewer words, or contact our 24/7 client helpdesk for dedicated assistance.
                    </p>
                    <a href="{{ route('hosting.storefront.contact') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition">
                        <i class="ti ti-headset"></i> Contact Support
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Category Grid -->
    <div class="space-y-6 mb-16">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-white">Client Dashboard Knowledge Modules</h2>
                <p class="text-xs text-gray-400 mt-1">Select a category to explore complete documentation and button breakdowns</p>
            </div>
            <span class="text-xs font-mono text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20">
                10 Core Modules Available
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $catSlug => $category)
                <div class="p-6 rounded-2xl bg-gradient-to-b from-white/[0.05] to-white/[0.02] border border-white/10 hover:border-indigo-500/40 transition flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-2xl shadow-inner">
                            <i class="{{ $category['icon'] }}"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white">
                            {{ $category['name'] }}
                        </h3>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            {{ $category['description'] }}
                        </p>

                        <!-- Article Links List -->
                        <div class="pt-3 border-t border-white/5 space-y-2">
                            @foreach($category['articles'] as $artSlug => $art)
                                <a href="{{ route('hosting.kb.article', [$catSlug, $artSlug]) }}" class="group/item flex items-center justify-between text-xs text-gray-300 hover:text-indigo-400 transition">
                                    <span class="truncate pr-2">&bull; {{ $art['title'] }}</span>
                                    <i class="ti ti-chevron-right text-gray-500 group-hover/item:translate-x-1 transition text-[10px]"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between text-xs">
                        <span class="text-gray-500">{{ count($category['articles']) }} {{ Str::plural('article', count($category['articles'])) }}</span>
                        <a href="{{ route('hosting.kb.category', $catSlug) }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition inline-flex items-center gap-1">
                            Browse Module &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Help & Support Banner -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-indigo-900/40 via-purple-900/30 to-pink-900/20 border border-indigo-500/30 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl">
        <div class="space-y-2 text-center md:text-left">
            <h3 class="text-xl font-black text-white">Still have questions or need custom server setup?</h3>
            <p class="text-xs text-gray-300 max-w-xl">
                Our Sri Lankan engineering team is standing by 24/7. We offer instant setup, port forwarding assistance, and free world migrations from previous hosts.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('hosting.storefront.contact') }}" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg">
                <i class="ti ti-headset"></i> Open Helpdesk Ticket
            </a>
            <a href="{{ $seo->support_discord_url ?: config('app.discord_url', 'https://discord.gg/shanutechx') }}" target="_blank" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-bold transition flex items-center gap-2">
                <i class="ti ti-brand-discord"></i> Join Discord
            </a>
        </div>
    </div>

</div>
@endsection
