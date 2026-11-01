@extends('hosting-platform::storefront.layout')

@section('title', $article['title'] . ' — Knowledge Base — STX Enterprises')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-8">
        <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
        <span>&rsaquo;</span>
        <a href="{{ route('hosting.kb.index') }}" class="hover:text-white transition">Knowledge Base</a>
        <span>&rsaquo;</span>
        <a href="{{ route('hosting.kb.category', $article['category_slug']) }}" class="hover:text-white transition">{{ $article['category_name'] }}</a>
        <span>&rsaquo;</span>
        <span class="text-indigo-400 font-semibold truncate max-w-sm">{{ $article['title'] }}</span>
    </nav>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

        <!-- Article Body -->
        <article class="lg:col-span-3 space-y-6">
            <!-- Article Header -->
            <div class="space-y-4 pb-8 border-b border-white/10">
                <div class="flex items-center gap-2 text-xs font-semibold text-indigo-400">
                    <i class="{{ $article['category_icon'] }}"></i>
                    <a href="{{ route('hosting.kb.category', $article['category_slug']) }}" class="hover:underline">
                        {{ $article['category_name'] }}
                    </a>
                    <span class="text-gray-600">&bull;</span>
                    <span class="text-gray-400">{{ $article['read_time'] }}</span>
                    <span class="text-gray-600">&bull;</span>
                    <span class="text-emerald-400">Verified for Pelican v1.0 / STX Core</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                    {{ $article['title'] }}
                </h1>
                <p class="text-base text-gray-300 leading-relaxed font-normal">
                    {{ $article['summary'] }}
                </p>
            </div>

            <!-- Article Content HTML -->
            <div class="prose prose-invert max-w-none text-gray-300 text-sm leading-relaxed space-y-6">
                {!! $article['content'] !!}
            </div>

            <!-- Helpful Rating Widget -->
            <div class="pt-8 mt-12 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 p-6 rounded-2xl bg-white/[0.02]">
                <div class="text-center sm:text-left">
                    <h4 class="text-sm font-bold text-white">Was this guide helpful?</h4>
                    <p class="text-xs text-gray-400 mt-0.5">Let us know if this answered your question or if you need additional details.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="alert('Thank you for your feedback!')" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-emerald-500/20 hover:text-emerald-300 text-gray-300 text-xs font-semibold border border-white/10 transition flex items-center gap-1.5">
                        <i class="ti ti-thumb-up"></i> Yes, very helpful
                    </button>
                    <button type="button" onclick="alert('Thank you! Our documentation engineers will review this article.')" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-red-500/20 hover:text-red-300 text-gray-300 text-xs font-semibold border border-white/10 transition flex items-center gap-1.5">
                        <i class="ti ti-thumb-down"></i> Needs update
                    </button>
                </div>
            </div>
        </article>

        <!-- Right Sticky Sidebar -->
        <aside class="space-y-6">
            <!-- Related in this Category -->
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/10 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-400">
                    More in {{ $article['category_name'] }}
                </h3>
                <div class="space-y-2 text-xs">
                    @if(isset($category['articles']))
                        @foreach($category['articles'] as $siblingSlug => $sibling)
                            <a href="{{ route('hosting.kb.article', [$article['category_slug'], $siblingSlug]) }}" class="block p-2.5 rounded-xl transition {{ $siblingSlug === $article['slug'] ? 'bg-indigo-600/20 text-indigo-300 font-bold border border-indigo-500/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                {{ $sibling['title'] }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Direct Quick Action Links -->
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/10 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Quick Dashboard Actions</h3>
                <div class="space-y-2 text-xs">
                    <a href="{{ url('/servers') }}" class="flex items-center gap-2 p-2 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white transition">
                        <i class="ti ti-brand-docker text-indigo-400"></i> My Game Servers
                    </a>
                    <a href="{{ url('/customer-orders') }}" class="flex items-center gap-2 p-2 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white transition">
                        <i class="ti ti-receipt-2 text-emerald-400"></i> My Orders & Invoices
                    </a>
                    <a href="{{ route('hosting.status.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white transition">
                        <i class="ti ti-activity-heartbeat text-cyan-400"></i> Live Telemetry Status
                    </a>
                    <a href="{{ route('hosting.storefront.contact') }}" class="flex items-center gap-2 p-2 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white transition">
                        <i class="ti ti-headset text-pink-400"></i> 24/7 Client Helpdesk
                    </a>
                </div>
            </div>
        </aside>

    </div>

</div>
@endsection
