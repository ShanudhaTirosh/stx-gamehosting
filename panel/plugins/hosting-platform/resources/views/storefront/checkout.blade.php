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
@php
    $setupFee = (float) $plan->setup_fee_lkr;
    $total = (float) $plan->price_lkr + $setupFee;
    $regionLabels = (array) config('hosting-platform.provisioning.region_labels', []);
    $fmt = fn ($n) => rtrim(rtrim(number_format($n, 1), '0'), '.');
@endphp
<section class="border-b stx-rule">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="stx-label mb-3">Checkout</div>
        <h1 class="stx-display text-5xl sm:text-6xl text-white">{{ $plan->name }}</h1>
        <p class="mt-3 text-[var(--mut)]">{{ $plan->product->name }}. You pay after placing the order, not on this page.</p>
    </div>
</section>

<section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        @if($errors->any())
            <div class="mb-8 p-4 border text-sm" style="border-color:var(--bad); background:rgba(255,92,92,.07); color:#FFB4B4" role="alert">
                <ul class="space-y-1">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hosting.checkout.process', $plan->slug) }}" method="POST" class="grid lg:grid-cols-12 gap-10 lg:gap-14">
            @csrf

            <div class="lg:col-span-7 space-y-12">
                {{-- 01 Server --}}
                <fieldset class="space-y-5">
                    <legend class="flex items-center gap-3 mb-5"><span class="stx-mono text-sm" style="color:var(--acc)">01</span><span class="text-xl font-semibold text-white">Your server</span></legend>
                    <div class="stx-field">
                        <label for="server_name" class="stx-label">Server name</label>
                        <input id="server_name" type="text" name="server_name" maxlength="60" value="{{ old('server_name', $plan->name . ' Server') }}" class="stx-input">
                        <p class="stx-hint">Shown in your control panel. You can rename it later.</p>
                    </div>
                    @if(count($regionLabels))
                    <div class="stx-field">
                        <label for="server_location" class="stx-label">Region</label>
                        <select id="server_location" name="server_location" class="stx-input">
                            @foreach($regionLabels as $code => $label)
                                <option value="{{ $code }}" @selected(old('server_location', array_key_first($regionLabels)) === $code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </fieldset>

                {{-- 02 Account --}}
                @guest
                <fieldset class="space-y-5">
                    <legend class="flex items-center gap-3 mb-2"><span class="stx-mono text-sm" style="color:var(--acc)">02</span><span class="text-xl font-semibold text-white">Your account</span></legend>
                    <p class="text-[15px] text-[var(--mut)]">Already registered? <a href="{{ route('filament.app.auth.login') }}" class="text-[var(--acc)] underline underline-offset-2">Sign in first</a>, then come back.</p>
                    <div class="stx-field">
                        <label for="guest_name" class="stx-label">Full name</label>
                        <input id="guest_name" type="text" name="guest_name" autocomplete="name" value="{{ old('guest_name') }}" class="stx-input" required>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="stx-field">
                            <label for="guest_email" class="stx-label">Email</label>
                            <input id="guest_email" type="email" name="guest_email" autocomplete="email" value="{{ old('guest_email') }}" class="stx-input" required>
                        <div class="border-t border-white/10 pt-4 flex justify-between items-baseline">
                        <div class="stx-field">
                            <label for="guest_password" class="stx-label">Password</label>
                            <input id="guest_password" type="password" name="guest_password" autocomplete="new-password" minlength="10" class="stx-input" required>
                            <p class="stx-hint">At least 10 characters.</p>
                        <button type="submit" class="w-full py-4 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2">
                    </div>
                </fieldset>
                @endguest

                {{-- 03 Payment --}}
                <fieldset class="space-y-4">
                    <legend class="flex items-center gap-3 mb-2"><span class="stx-mono text-sm" style="color:var(--acc)">{{ auth()->check() ? '02' : '03' }}</span><span class="text-xl font-semibold text-white">How you will pay</span></legend>
                    <p class="text-[15px] text-[var(--mut)]">Pick a method. The account or wallet details appear on your order page right after you place the order.</p>
                    @forelse($paymentMethods as $method)
                        <label class="stx-choice">
                            <input type="radio" name="payment_method_id" value="{{ $method->id }}" @checked(old('payment_method_id', $paymentMethods->first()->id) == $method->id) required>
                            <span>
                                <span class="block font-semibold text-white">{{ $method->name }}</span>
                                @if($method->description)<span class="block text-sm text-[var(--mut)] mt-0.5">{{ $method->description }}</span>@endif
                            </span>
                        </label>
                    @empty
                        <div class="p-4 border text-sm" style="border-color:var(--warn); color:#FFD28A">No payment methods are available right now. Please contact us to place an order.</div>
                    @endforelse
                </fieldset>
            </div>
                            <i class="ti ti-lock"></i> Place Order & Continue
            {{-- Summary --}}
            <aside class="lg:col-span-5">
                <div class="stx-panel lg:sticky lg:top-24">
                    <div class="px-5 py-4 border-b stx-rule flex items-center justify-between">
                        <span class="stx-label">Order summary</span>
                        <span class="text-sm text-[var(--mut)]">{{ $plan->product->name }}</span>
                    </div>
                    <dl class="px-5 py-4 space-y-2.5 text-[15px]">
                        <div class="flex justify-between"><dt class="text-[var(--mut)]">RAM</dt><dd class="stx-mono text-white">{{ $fmt($plan->memory_gb) }} GB</dd></div>
                        <div class="flex justify-between"><dt class="text-[var(--mut)]">CPU</dt><dd class="stx-mono text-white">{{ $plan->cpu_percent }}%</dd></div>
                        <div class="flex justify-between"><dt class="text-[var(--mut)]">Disk</dt><dd class="stx-mono text-white">{{ $fmt($plan->disk_gb) }} GB</dd></div>
                        @if($plan->database_limit > 0)<div class="flex justify-between"><dt class="text-[var(--mut)]">Databases</dt><dd class="stx-mono text-white">{{ $plan->database_limit }}</dd></div>@endif
                        @if($plan->backup_limit > 0)<div class="flex justify-between"><dt class="text-[var(--mut)]">Backup slots</dt><dd class="stx-mono text-white">{{ $plan->backup_limit }}</dd></div>@endif
                    </dl>
                    <dl class="px-5 py-4 border-t stx-rule space-y-2.5 text-[15px]">
                        <div class="flex justify-between"><dt class="text-[var(--mut)]">{{ $plan->name }} / {{ strtolower($plan->billing_period->getLabel()) }}</dt><dd class="stx-mono text-white">Rs. {{ number_format((float) $plan->price_lkr, 2) }}</dd></div>
                        @if($setupFee > 0)<div class="flex justify-between"><dt class="text-[var(--mut)]">Setup fee</dt><dd class="stx-mono text-white">Rs. {{ number_format($setupFee, 2) }}</dd></div>@endif
                    </dl>
                    <div class="px-5 py-5 border-t stx-rule flex items-baseline justify-between">
                        <span class="text-white font-semibold">Total due</span>
                        <span class="stx-mono text-2xl" style="color:var(--acc)">Rs. {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="px-5 pb-5">
                        <button type="submit" class="stx-btn w-full justify-center" @disabled($paymentMethods->isEmpty())>Place order <i class="ti ti-arrow-right"></i></button>
                        <p class="stx-hint mt-3">Nothing is charged now. Your server is deployed after we verify your payment slip. By ordering you accept the <a href="{{ route('hosting.storefront.terms') }}" class="underline">Terms</a>.</p>
            </div>
        </form>
            </aside>
</section>
@endsection
