@extends('hosting-platform::storefront.layout')

@section('title', $category['name'] . ' — Knowledge Base — STX Enterprises')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-8">
        <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
        <span>&rsaquo;</span>
        <a href="{{ route('hosting.kb.index') }}" class="hover:text-white transition">Knowledge Base</a>
        <span>&rsaquo;</span>
        <span class="text-indigo-400 font-semibold">{{ $category['name'] }}</span>
    </nav>

    <!-- Category Header -->
    <div class="flex items-start gap-5 p-8 rounded-3xl bg-white/[0.03] border border-white/10 mb-10">
        <div class="w-16 h-16 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-3xl shadow-inner shrink-0">
            <i class="{{ $category['icon'] }}"></i>
        </div>
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-white">{{ $category['name'] }}</h1>
            <p class="text-sm text-gray-400 leading-relaxed max-w-2xl">{{ $category['description'] }}</p>
            <div class="flex items-center gap-3 text-xs text-gray-500 pt-1">
                <span>{{ count($category['articles']) }} {{ Str::plural('article', count($category['articles'])) }} in this module</span>
            </div>
        </div>
    </div>

    <!-- Layout: Left Articles / Right Categories Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Articles List -->
        <div class="lg:col-span-3 space-y-4">
            @foreach($category['articles'] as $artSlug => $art)
                <a href="{{ route('hosting.kb.article', [$categorySlug, $artSlug]) }}" class="p-6 rounded-2xl bg-white/[0.02] hover:bg-white/[0.06] border border-white/10 hover:border-indigo-500/40 transition block group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-mono text-indigo-400 bg-indigo-500/10 px-2.5 py-0.5 rounded-full border border-indigo-500/20">
                            {{ $art['read_time'] }}
                        </span>
                        <span class="text-xs text-gray-500 group-hover:text-indigo-400 transition font-semibold">Read Guide &rarr;</span>
                    </div>
                    <h2 class="text-lg font-bold text-white group-hover:text-indigo-300 transition mb-2">
                        {{ $art['title'] }}
                    </h2>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        {{ $art['summary'] }}
                    </p>
                </a>
            @endforeach
        </div>

        <!-- Sidebar Navigation -->
        <div class="space-y-6">
            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/10 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-400">All Help Modules</h3>
                <div class="space-y-1 text-xs">
                    @foreach($allCategories as $cSlug => $cat)
                        <a href="{{ route('hosting.kb.category', $cSlug) }}" class="flex items-center justify-between p-2 rounded-lg transition {{ $cSlug === $categorySlug ? 'bg-indigo-600/20 text-indigo-300 font-bold border border-indigo-500/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                            <span class="flex items-center gap-2">
                                <i class="{{ $cat['icon'] }}"></i>
                                <span>{{ $cat['name'] }}</span>
                            </span>
                            <span class="text-[10px] opacity-60">{{ count($cat['articles']) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-gradient-to-b from-indigo-950/40 to-transparent border border-indigo-500/20 text-center space-y-3">
                <i class="ti ti-headset text-3xl text-indigo-400"></i>
                <h4 class="text-sm font-bold text-white">Need Personal Assistance?</h4>
                <p class="text-xs text-gray-400">Our Colombo support desk is available 24 hours a day, 7 days a week.</p>
                <a href="{{ route('hosting.storefront.contact') }}" class="block px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition">
                    Submit Ticket
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
