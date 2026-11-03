@extends('hosting-platform::storefront.layout')

@section('title', 'Order #' . $order->order_number . ' Status')

@section('content')
@php
    $OS = \BrandedHosting\Platform\Enums\OrderStatus::class;
    $VS = \BrandedHosting\Platform\Enums\VerificationStatus::class;

    $st = $order->status;
    $isOpen = in_array($st, [$OS::PendingPayment, $OS::Rejected], true);
    $isReview = $st === $OS::UnderReview;
    $isBuilding = in_array($st, [$OS::Approved, $OS::Provisioning], true);
    $isDone = $st === $OS::Completed;
    $isDead = in_array($st, [$OS::Cancelled, $OS::Expired], true);

    $chip = match (true) {
        $isDone => 'var(--ok)',
        $isDead || $st === $OS::Rejected => 'var(--bad)',
        default => 'var(--warn)',
    };

    // Which of the four steps are complete.
    $reached = match (true) {
        $isDone => 4,
        $isBuilding => 3,
        $isReview => 2,
        default => 1,
    };
    $steps = ['Order placed', 'Payment slip sent', 'Payment verified', 'Server live'];

    $snap = $order->plan_snapshot ?? [];
    $fmt = fn ($n) => rtrim(rtrim(number_format($n, 1), '0'), '.');
    $pm = $order->paymentMethod;
    $lastRejected = $order->paymentSubmissions->where('verification_status', $VS::Rejected)->sortByDesc('created_at')->first();
    $renewsOrder = $order->renewsOrder ?? null;
@endphp

<section class="border-b stx-rule">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        {{-- Breadcrumb navigation back to Client Panel and Orders --}}
        <div class="flex items-center gap-2 text-sm text-[var(--mut)] mb-6">
            <a href="/app" class="hover:text-white transition">Client Panel</a>
            <span>/</span>
            <a href="/app/customer-orders" class="hover:text-white transition">My Orders & Billing</a>
            <span>/</span>
            <span class="text-white">Order {{ $order->order_number }}</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="stx-label">{{ $order->isRenewal() ? 'Renewal Order' : 'Order' }}</span>
                    <span class="stx-mono text-sm text-white select-all">{{ $order->order_number }}</span>
                    <span class="inline-flex items-center gap-2 text-sm font-medium text-white">
                        <span class="stx-dot" style="background: {{ $chip }}"></span>
                        {{ $st->getLabel() }}
                    </span>
                </div>
                <h1 class="stx-display text-4xl sm:text-5xl text-white">{{ $order->plan?->name ?? ($snap['plan_name'] ?? 'Server plan') }}</h1>
                <p class="mt-2 text-[var(--mut)]">
                    {{ $order->isRenewal() ? 'Issued' : 'Placed' }} {{ $order->created_at->format('j M Y, g:i A') }}
                    @if($renewsOrder)
                        &middot; renews <a href="{{ route('hosting.order.tracking', $renewsOrder->uuid) }}" class="underline text-[var(--acc)]">{{ $renewsOrder->order_number }}</a>
                    @endif
                </p>
            </div>
            <div class="lg:text-right">
                <div class="stx-label">Total</div>
                <div class="stx-mono text-3xl text-white">{{ $order->formatted_total }}</div>
                <a href="{{ route('hosting.order.invoice', $order->uuid) }}" target="_blank" rel="noopener" class="inline-block mt-2 text-sm text-[var(--acc)] underline underline-offset-2">View invoice</a>
            </div>
        </div>

        @unless($isDead)
        <ol class="mt-10 grid grid-cols-2 sm:grid-cols-4 border-t border-l stx-rule" aria-label="Order progress">
            @foreach($steps as $i => $label)
                @php $n = $i + 1; $on = $n <= $reached; $now = $n === $reached && !$isDone; @endphp
                <li class="p-4 border-r border-b stx-rule" @if($now) aria-current="step" @endif>
                    <div class="stx-mono text-xs" style="color: {{ $on ? 'var(--acc)' : 'var(--mut)' }}">{{ str_pad($n, 2, '0', STR_PAD_LEFT) }} {{ $on && !$now ? '/ done' : ($now ? '/ now' : '') }}</div>
                    <div class="mt-1 font-medium {{ $on ? 'text-white' : 'text-[var(--mut)]' }}">{{ $label }}</div>
                </li>
            @endforeach
        </ol>
        @endunless
    </div>
</section>

<section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 grid lg:grid-cols-12 gap-10 lg:gap-14">
        <div class="lg:col-span-8 space-y-10">

            @if(session('success'))
                <div class="p-4 border text-sm" style="border-color:var(--ok); background:rgba(61,220,132,.07); color:#B6F2D0" role="status">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="p-4 border text-sm" style="border-color:var(--bad); background:rgba(255,92,92,.07); color:#FFB4B4" role="alert">
                    <ul class="space-y-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- STATE: waiting for payment / rejected / under review --}}
            @if($isOpen || $isReview)
                @if($st === $OS::Rejected)
                    <div class="p-4 border text-sm" style="border-color:var(--bad); background:rgba(255,92,92,.07); color:#FFB4B4">
                        <strong class="text-white">We could not verify your last payment.</strong>
                        @if($lastRejected?->staff_decision_notes) {{ $lastRejected->staff_decision_notes }} @endif
                        Please send a new slip below.
                    </div>
                @endif

                @if($isReview)
                    <div class="stx-panel p-6">
                        <div class="flex items-center gap-3">
                            <span class="stx-dot" style="background:var(--warn)"></span>
                            <span class="text-xl font-semibold text-white">We are verifying your payment.</span>
                        </div>
                        <p class="mt-2 text-[15px] text-[var(--mut)]">A member of our billing team verifies every slip. This page will update automatically once verified; you can safely close it.</p>
                    </div>
                @endif

                @if($isOpen)
                <div>
                    <h2 class="flex items-center gap-3 mb-5">
                        <span class="stx-mono text-sm" style="color:var(--acc)">01</span>
                        <span class="text-xl font-semibold text-white">Transfer {{ $order->formatted_total }}</span>
                    </h2>
                    @if($pm)
                        <div class="stx-panel">
                            <div class="px-5 py-3 border-b stx-rule text-white font-semibold">{{ $pm->name }}</div>
                            <dl class="px-5 py-4 grid sm:grid-cols-2 gap-x-6 gap-y-4 text-[15px]">
                                @foreach([['Bank', $pm->bank_name], ['Branch', $pm->branch_name], [$pm->bank_name ? 'Account number' : 'Number', $pm->account_or_mobile_number], ['Account name', $pm->recipient_name]] as [$k, $v])
                                    @if($v)
                                        <div>
                                            <dt class="stx-label">{{ $k }}</dt>
                                            <dd class="mt-1 stx-mono text-white select-all break-all {{ str_contains($k, 'number') || $k === 'Number' ? 'text-lg' : '' }}" @if(str_contains($k, 'number') || $k === 'Number') style="color:var(--acc)" @endif>{{ $v }}</dd>
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                            @if($pm->payment_instructions)
                                <div class="px-5 py-4 border-t stx-rule text-[15px] text-[var(--mut)] whitespace-pre-line">{{ $pm->payment_instructions }}</div>
                            @endif
                        </div>
                    @endif
                </div>
                @endif

                <div>
                    <h2 class="flex items-center gap-3 mb-5">
                        <span class="stx-mono text-sm" style="color:var(--acc)">{{ $isOpen ? '02' : '+' }}</span>
                        <span class="text-xl font-semibold text-white">{{ $isOpen ? 'Upload your payment slip' : 'Need to send another slip?' }}</span>
                    </h2>
                    <form action="{{ route('hosting.order.submit_proof', $order->uuid) }}" method="POST" enctype="multipart/form-data" class="stx-panel p-5 space-y-5">
                        @csrf
                        <input type="hidden" name="payment_method_id" value="{{ $order->payment_method_id ?? $paymentMethods->first()?->id }}">
                        <div class="stx-field">
                            <label for="transaction_reference" class="stx-label">Transaction reference</label>
                            <input id="transaction_reference" type="text" name="transaction_reference" maxlength="100" placeholder="e.g. 1029384756" value="{{ old('transaction_reference') }}" class="stx-input" required>
                        </div>
                        <div class="stx-field">
                            <label for="proof_file" class="stx-label">Slip (JPG, PNG, WEBP or PDF, up to {{ round(config('hosting-platform.proof_uploads.max_file_size_kb', 10240) / 1024) }} MB)</label>
                            <input id="proof_file" type="file" name="proof_file" accept="image/jpeg,image/png,image/webp,application/pdf" class="stx-input" required>
                            <p class="stx-hint">Each slip can only be used once.</p>
                        </div>
                        <div class="stx-field">
                            <label for="customer_notes" class="stx-label">Note for our team (optional)</label>
                            <textarea id="customer_notes" name="customer_notes" rows="2" maxlength="1000" class="stx-input">{{ old('customer_notes') }}</textarea>
                        </div>
                        <button type="submit" class="stx-btn">Send slip <i class="ti ti-upload"></i></button>
                    </form>
                </div>

            {{-- STATE: approved, server being built --}}
            @elseif($isBuilding)
                <div class="stx-panel p-6">
                    <div class="flex items-center gap-3">
                        <span class="stx-dot" style="background:var(--warn)"></span>
                        <span class="text-xl font-semibold text-white">Payment verified. Setting up your server.</span>
                    </div>
                    <p class="mt-2 text-[15px] text-[var(--mut)]">This usually takes a few minutes. Reload this page to check; it will show a link to your server as soon as it is ready. If nothing changes after 30 minutes, <a href="{{ route('hosting.storefront.contact') }}" class="underline">contact us</a> with order {{ $order->order_number }}.</p>
                </div>

            {{-- STATE: live --}}
            @elseif($isDone)
                <div class="stx-panel p-6">
                    <div class="flex items-center gap-3">
                        <span class="stx-dot" style="background:var(--ok)"></span>
                        <span class="text-xl font-semibold text-white">{{ $order->isRenewal() ? 'Subscription Extended.' : 'Your server is live.' }}</span>
                    </div>
                    @if($order->server)
                        <dl class="mt-5 grid sm:grid-cols-2 gap-4 text-[15px]">
                            @if($order->server->allocation)
                                <div>
                                    <dt class="stx-label">Address</dt>
                                    <dd class="mt-1 stx-mono text-white select-all">{{ $order->server->allocation->display_address ?? 'Configuring' }}</dd>
                                </div>
                            @endif
                            @if($order->server->node)
                                <div>
                                    <dt class="stx-label">Node</dt>
                                    <dd class="mt-1 stx-mono text-white">{{ $order->server->node->name }}</dd>
                                </div>
                            @endif
                        </dl>
                        <div class="mt-6 flex flex-wrap gap-4">
                            <a href="/server/{{ $order->server->uuid_short }}" class="stx-btn">Open control panel <i class="ti ti-arrow-up-right"></i></a>
                            <a href="/app" class="stx-btn-secondary">View all servers</a>
                        </div>
                    @elseif($renewsOrder && $renewsOrder->server)
                        <div class="mt-6 flex flex-wrap gap-4">
                            <a href="/server/{{ $renewsOrder->server->uuid_short }}" class="stx-btn">Open control panel <i class="ti ti-arrow-up-right"></i></a>
                            <a href="/app" class="stx-btn-secondary">View all servers</a>
                        </div>
                    @endif
                </div>

            {{-- STATE: cancelled / expired --}}
            @elseif($isDead)
                <div class="stx-panel p-6">
                    <div class="text-xl font-semibold text-white">This order is {{ strtolower($st->getLabel()) }}.</div>
                    <p class="mt-2 text-[15px] text-[var(--mut)]">No server was created and nothing further is needed. If you already paid, please <a href="{{ route('hosting.storefront.contact') }}" class="underline">contact us</a> with the order number.</p>
                    @if($order->plan)
                        <a href="{{ route('hosting.checkout.show', $order->plan->slug) }}" class="stx-btn mt-5">Order again</a>
                    @endif
                </div>
            @endif

            {{-- Submission history --}}
            @if($order->paymentSubmissions->isNotEmpty())
                <div>
                    <h2 class="stx-label mb-3">Payment slips sent</h2>
                    <div class="stx-panel divide-y" style="--tw-divide-opacity:1">
                        @foreach($order->paymentSubmissions->sortByDesc('created_at') as $sub)
                            @php $vc = match ($sub->verification_status) { $VS::Approved => 'var(--ok)', $VS::Rejected => 'var(--bad)', default => 'var(--warn)' }; @endphp
                            <div class="px-5 py-4 border-b stx-rule last:border-b-0 flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <div class="stx-mono text-sm text-white">Ref {{ $sub->transaction_reference }}</div>
                                    <div class="text-sm text-[var(--mut)]">{{ $sub->created_at->format('j M Y, g:i A') }}</div>
                                    @if($sub->staff_decision_notes)<div class="text-sm text-[var(--mut)] mt-1">Note from staff: {{ $sub->staff_decision_notes }}</div>@endif
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="inline-flex items-center gap-2 text-sm text-white"><span class="stx-dot" style="background:{{ $vc }}"></span>{{ $sub->verification_status->getLabel() }}</span>
                                    @if($sub->proofs->isNotEmpty())<a href="{{ $sub->proofs->first()->getDownloadUrl() }}" target="_blank" rel="noopener" class="text-sm text-[var(--acc)] underline underline-offset-2">View slip</a>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Spec sidebar --}}
        <aside class="lg:col-span-4">
            <div class="stx-panel lg:sticky lg:top-24">
                <div class="px-5 py-4 border-b stx-rule stx-label">What you ordered</div>
                <dl class="px-5 py-4 space-y-2.5 text-[15px]">
                    <div class="flex justify-between"><dt class="text-[var(--mut)]">Game</dt><dd class="text-white">{{ $snap['product_name'] ?? ($order->plan?->product?->name ?? '') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-[var(--mut)]">RAM</dt><dd class="stx-mono text-white">{{ $fmt(($snap['memory_mb'] ?? 0) / 1024) }} GB</dd></div>
                    <div class="flex justify-between"><dt class="text-[var(--mut)]">CPU</dt><dd class="stx-mono text-white">{{ $snap['cpu_percent'] ?? 0 }}%</dd></div>
                    <div class="flex justify-between"><dt class="text-[var(--mut)]">Disk</dt><dd class="stx-mono text-white">{{ $fmt(($snap['disk_mb'] ?? 0) / 1024) }} GB</dd></div>
                    @if(($snap['database_limit'] ?? 0) > 0)<div class="flex justify-between"><dt class="text-[var(--mut)]">Databases</dt><dd class="stx-mono text-white">{{ $snap['database_limit'] }}</dd></div>@endif
                    @if(($snap['backup_limit'] ?? 0) > 0)<div class="flex justify-between"><dt class="text-[var(--mut)]">Backup slots</dt><dd class="stx-mono text-white">{{ $snap['backup_limit'] }}</dd></div>@endif
                </dl>
                <div class="px-5 py-4 border-t stx-rule text-sm text-[var(--mut)]">Questions? <a href="{{ route('hosting.storefront.contact') }}" class="text-[var(--acc)] underline underline-offset-2">Contact us</a> and quote {{ $order->order_number }}.</div>
            </div>
        </aside>
    </div>
</section>
@endsection
