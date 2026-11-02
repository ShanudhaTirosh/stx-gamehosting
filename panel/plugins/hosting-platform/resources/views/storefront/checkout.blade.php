@extends('hosting-platform::storefront.layout')

@section('title', 'Order ' . $plan->name . ' - ' . $plan->product->name . ' - STX Enterprises')
@section('meta_description', $plan->getSeoMetaDescription())
@section('meta_keywords', $plan->getSeoKeywords())
@section('og_image', url($plan->display_image))

@section('extra_schema')
@php
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $plan->name . ' - ' . $plan->product->name,
        'image' => url($plan->display_image),
        'description' => $plan->getSeoMetaDescription(),
        'sku' => $plan->slug,
        'offers' => [
            '@type' => 'Offer',
            'url' => url()->current(),
            'priceCurrency' => 'LKR',
            'price' => (string) $plan->price_lkr,
            'priceValidUntil' => date('Y-12-31'),
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => 'https://schema.org/InStock',
            'seller' => [
                '@type' => 'Organization',
                'name' => 'STX Enterprises Lanka (Pvt) Ltd',
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<section class="py-12 md:py-20 relative overflow-hidden">
    <!-- Ambient Glow Background -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="mb-10 text-center space-y-2">
            <h1 class="text-3xl sm:text-4xl font-black text-white">Order Checkout</h1>
            <p class="text-sm text-gray-400">Configure your instance and select your preferred payment rail (Bank Transfer / eZ Cash / HelaPay).</p>
        </div>

        @if($errors->any())
            <div class="mb-8 p-4 rounded-2xl bg-red-950/60 border border-red-500/30 text-red-300 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hosting.checkout.process', $plan->slug) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Configuration & Details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Server Options -->
                    <div class="glass-card p-6 sm:p-8 rounded-3xl">
                        <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <i class="ti ti-server-cog text-indigo-400"></i> Server Details
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-300 mb-1.5">Server Hostname / Name</label>
                                <input type="text" name="server_name" value="{{ old('server_name', $plan->name . ' Server') }}" class="w-full px-4 py-3 rounded-xl bg-black/50 border border-white/10 text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                                <span class="text-[10px] text-gray-500">How your instance will be identified in your console.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-300 mb-1.5">Hosting Region</label>
                                <select name="server_location" class="w-full px-4 py-3 rounded-xl bg-black/50 border border-white/10 text-white text-sm focus:border-indigo-500 focus:outline-none">
                                    <option value="sg">Singapore / Asia Pacific (Lowest Latency to Sri Lanka)</option>
                                    <option value="de">Frankfurt / Europe</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Account Info if Guest -->
                    @guest
                    <div class="glass-card p-6 sm:p-8 rounded-3xl">
                        <h2 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                            <i class="ti ti-user-plus text-indigo-400"></i> Account Creation
                        </h2>
                        <p class="text-xs text-gray-400 mb-6">Already have an account? <a href="{{ route('filament.app.auth.login') }}" class="text-indigo-400 font-semibold hover:underline">Log in here</a></p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-300 mb-1.5">Your Full Name</label>
                                <input type="text" name="guest_name" value="{{ old('guest_name') }}" class="w-full px-4 py-3 rounded-xl bg-black/50 border border-white/10 text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="e.g. Kasun Perera" required>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Email Address</label>
                                    <input type="email" name="guest_email" value="{{ old('guest_email') }}" class="w-full px-4 py-3 rounded-xl bg-black/50 border border-white/10 text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="you@domain.lk" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Account Password</label>
                                    <input type="password" name="guest_password" class="w-full px-4 py-3 rounded-xl bg-black/50 border border-white/10 text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Min. 10 characters" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest

                    <!-- Payment Method Selection -->
                    <div class="glass-card p-6 sm:p-8 rounded-3xl">
                        <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <i class="ti ti-wallet text-indigo-400"></i> Select Payment Method
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach($paymentMethods as $method)
                                <label class="flex items-start gap-4 p-4 rounded-2xl border border-white/10 bg-black/40 hover:border-indigo-500/50 cursor-pointer transition">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="mt-1 text-indigo-600 focus:ring-indigo-500" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-white text-sm">{{ $method->name }}</span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Manual Slip Verification</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">{{ $method->description }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary Sticky Card -->
                <div class="space-y-6">
                    <div class="glass-card p-6 sm:p-8 rounded-3xl sticky top-28 space-y-6">
                        <h2 class="text-lg font-bold text-white border-b border-white/10 pb-4">Order Summary</h2>

                        <div>
                            <!-- Plan Thumbnail & Ribbon -->
                            <div class="relative w-full h-28 rounded-2xl overflow-hidden mb-4 bg-black/40 border border-white/5">
                                <img src="{{ $plan->display_image }}" alt="{{ $plan->name }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-indigo-600/90 text-white shadow">
                                        {{ $plan->display_badge }}
                                    </span>
                                </div>
                                <div class="absolute bottom-2 left-3">
                                    <span class="text-xs font-bold text-white">{{ $plan->name }}</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-white text-base">{{ $plan->name }}</div>
                                    <div class="text-xs text-indigo-400 mt-0.5">{{ $plan->product->name }}</div>
                                </div>
                                <span class="font-mono font-bold text-white">Rs. {{ number_format((float) $plan->price_lkr, 2) }}</span>
                            </div>
                            
                            <div class="mt-4 p-3 rounded-xl bg-black/40 border border-white/5 space-y-1.5 text-xs text-gray-400">
                                <div class="flex justify-between"><span>RAM:</span> <strong class="text-white">{{ number_format($plan->memory_mb / 1024, 0) }} GB</strong></div>
                                <div class="flex justify-between"><span>CPU:</span> <strong class="text-white">{{ $plan->cpu_percent }}%</strong></div>
                                <div class="flex justify-between"><span>NVMe Disk:</span> <strong class="text-white">{{ number_format($plan->disk_mb / 1024, 0) }} GB</strong></div>
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-4 space-y-2 text-xs">
                            <div class="flex justify-between text-gray-400">
                                <span>Cycle:</span>
                                <span class="font-semibold text-white capitalize">{{ $plan->billing_period->getLabel() }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Setup Fee:</span>
                                <span class="font-semibold text-emerald-400">FREE (Rs. 0.00)</span>
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-4 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-white">Total Due:</span>
                            <div class="text-right">
                                <span class="text-2xl font-black font-mono text-indigo-400">Rs. {{ number_format((float) $plan->price_lkr, 2) }}</span>
                                <span class="text-[10px] text-gray-400 block font-semibold">LKR (Sri Lankan Rupee)</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2">
                            <i class="ti ti-lock"></i> Place Order & Continue
                        </button>

                        <div class="text-center text-[10px] text-gray-500">
                            By placing this order you agree to the STX Enterprises Terms of Service.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
