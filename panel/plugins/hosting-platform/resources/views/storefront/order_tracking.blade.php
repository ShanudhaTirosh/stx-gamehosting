@extends('hosting-platform::storefront.layout')

@section('title', 'Order #' . $order->order_number . ' Status')

@section('content')
<section class="py-12 md:py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Status Banner -->
        <div class="mb-10 p-6 sm:p-8 rounded-3xl bg-gray-900 border border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-2xl">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl 
                    @if($order->status === \BrandedHosting\Platform\Enums\OrderStatus::Completed) bg-emerald-950 text-emerald-400 border border-emerald-800
                    @elseif($order->status === \BrandedHosting\Platform\Enums\OrderStatus::UnderReview || $order->status === \BrandedHosting\Platform\Enums\OrderStatus::Approved || $order->status === \BrandedHosting\Platform\Enums\OrderStatus::Provisioning) bg-blue-950 text-blue-400 border border-blue-800
                    @elseif($order->status === \BrandedHosting\Platform\Enums\OrderStatus::Rejected) bg-red-950 text-red-400 border border-red-800
                    @else bg-amber-950 text-amber-400 border border-amber-800 @endif">
                    <i class="ti {{ $order->status->getIcon() }}"></i>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-gray-400">{{ $order->order_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold 
                            @if($order->status === \BrandedHosting\Platform\Enums\OrderStatus::Completed) bg-emerald-500/20 text-emerald-300
                            @elseif($order->status === \BrandedHosting\Platform\Enums\OrderStatus::Rejected) bg-red-500/20 text-red-300
                            @elseif($order->status === \BrandedHosting\Platform\Enums\OrderStatus::UnderReview) bg-blue-500/20 text-blue-300
                            @else bg-amber-500/20 text-amber-300 @endif">
                            {{ $order->status->getLabel() }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-white mt-1">{{ $order->plan?->name ?? 'Server Plan' }}</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Created on {{ $order->created_at->format('M d, Y • h:i A') }}</p>
                </div>
            </div>

            <div class="text-right sm:border-l sm:border-gray-800 sm:pl-8">
                <div class="text-xs text-gray-400">Total Payment Due</div>
                <div class="text-3xl font-black text-white">{{ $order->formatted_total }}</div>
                <div class="mt-3 flex items-center gap-2 justify-end">
                    <a href="{{ route('hosting.order.invoice', $order->uuid) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-gray-200 bg-white/10 hover:bg-white/20 transition border border-white/10 shadow-sm">
                        <i class="ti ti-receipt text-indigo-400"></i> Invoice PDF
                    </a>
                    @if($order->server)
                        <a href="/server/{{ $order->server->uuid_short }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-lg shadow-emerald-600/30">
                            <i class="ti ti-terminal-2"></i> Console
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-sm flex items-center gap-3">
                <i class="ti ti-circle-check text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-950/80 border border-red-800 text-red-300 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Payment Instructions & Verification -->
            <div class="lg:col-span-2 space-y-8">
                <!-- If Pending or Rejected, show instructions and upload form -->
                @if($order->status === \BrandedHosting\Platform\Enums\OrderStatus::PendingPayment || $order->status === \BrandedHosting\Platform\Enums\OrderStatus::Rejected || $order->status === \BrandedHosting\Platform\Enums\OrderStatus::UnderReview)
                    <div class="p-6 sm:p-8 rounded-2xl bg-gray-900 border border-gray-800">
                        <h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                            <i class="ti ti-info-circle text-brand-400"></i> Payment Instructions
                        </h2>
                        <p class="text-xs text-gray-400 mb-6">Please transfer the exact amount using one of our verified Sri Lankan payment methods.</p>

                        @if($order->paymentMethod)
                            <div class="p-5 rounded-2xl bg-gray-950 border border-gray-800 space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-800">
                                    <span class="text-sm font-bold text-white">{{ $order->paymentMethod->name }}</span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-brand-950 text-brand-300 border border-brand-800">Selected Method</span>
                                </div>

                                @if($order->paymentMethod->bank_name)
                                    <div class="grid grid-cols-2 gap-4 text-xs">
                                        <div>
                                            <span class="text-gray-400">Bank Name:</span>
                                            <div class="font-bold text-white text-sm mt-0.5">{{ $order->paymentMethod->bank_name }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-400">Branch:</span>
                                            <div class="font-bold text-white text-sm mt-0.5">{{ $order->paymentMethod->branch_name ?? 'Head Office' }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-400">Account Number:</span>
                                            <div class="font-mono font-bold text-brand-400 text-base mt-0.5 select-all">{{ $order->paymentMethod->account_or_mobile_number }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-400">Account Name:</span>
                                            <div class="font-bold text-white text-sm mt-0.5">{{ $order->paymentMethod->recipient_name }}</div>
                                        </div>
                                    </div>
                                @elseif($order->paymentMethod->account_or_mobile_number)
                                    <div class="text-xs">
                                        <span class="text-gray-400">Mobile / Wallet Number:</span>
                                        <div class="font-mono font-bold text-brand-400 text-lg mt-0.5 select-all">{{ $order->paymentMethod->account_or_mobile_number }}</div>
                                        <div class="text-gray-400 mt-1">Recipient: <strong class="text-white">{{ $order->paymentMethod->recipient_name }}</strong></div>
                                    </div>
                                @endif

                                <div class="pt-3 border-t border-gray-800 text-xs text-gray-300 leading-relaxed whitespace-pre-line">
                                    {{ $order->paymentMethod->payment_instructions }}
                                </div>
                            </div>
                        @endif

                        <!-- Proof Upload Form -->
                        <div class="mt-8 pt-8 border-t border-gray-800">
                            <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
                                <i class="ti ti-upload text-brand-400"></i> Submit Payment Verification Proof
                            </h3>
                            <p class="text-xs text-gray-400 mb-6">Upload your deposit slip, bank transfer screenshot, or eZ Cash/HelaPay confirmation SMS receipt.</p>

                            <form action="{{ route('hosting.order.submit_proof', $order->uuid) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="hidden" name="payment_method_id" value="{{ $order->payment_method_id ?? $paymentMethods->first()?->id }}">

                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Bank Reference / eZ Cash Txn ID / HelaPay Ref *</label>
                                    <input type="text" name="transaction_reference" placeholder="e.g. 1029384756 or CDM-8821" value="{{ old('transaction_reference') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-950 border border-gray-800 text-white text-sm focus:border-brand-500 focus:outline-none" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Attach Proof Slip / Screenshot (JPG, PNG, WEBP, PDF - Max 10MB) *</label>
                                    <input type="file" name="proof_file" accept="image/jpeg,image/png,image/webp,application/pdf" class="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-600 file:text-white hover:file:bg-brand-500 cursor-pointer" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Optional Customer Notes</label>
                                    <textarea name="customer_notes" rows="2" placeholder="Any additional notes for verification staff..." class="w-full px-3.5 py-2 rounded-xl bg-gray-950 border border-gray-800 text-white text-sm focus:border-brand-500 focus:outline-none">{{ old('customer_notes') }}</textarea>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 shadow-lg shadow-brand-500/20 transition">
                                    Submit Proof for Verification <i class="ti ti-arrow-right ml-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Active or Completed State -->
                    <div class="p-8 rounded-2xl bg-gray-900 border border-gray-800 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-950 border border-emerald-800 text-emerald-400 flex items-center justify-center text-3xl mx-auto">
                            <i class="ti ti-check"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Your Server Is Ready!</h2>
                        <p class="text-sm text-gray-300 max-w-md mx-auto">
                            Payment was verified and your container has been allocated and started on our server infrastructure.
                        </p>
                        @if($order->server)
                            <div class="pt-4">
                                <a href="/server/{{ $order->server->uuid_short }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-500 shadow-xl shadow-emerald-600/30 transition">
                                    <i class="ti ti-terminal-2 text-lg"></i> Manage in Server Console
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Payment Submissions History -->
                @if($order->paymentSubmissions->count() > 0)
                    <div class="p-6 rounded-2xl bg-gray-900 border border-gray-800">
                        <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                            <i class="ti ti-history text-brand-400"></i> Verification Audit Log
                        </h3>
                        <div class="space-y-4">
                            @foreach($order->paymentSubmissions as $submission)
                                <div class="p-4 rounded-xl bg-gray-950 border border-gray-800 flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-white">Ref: {{ $submission->transaction_reference }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                                @if($submission->verification_status === \BrandedHosting\Platform\Enums\VerificationStatus::Approved) bg-emerald-950 text-emerald-300 border border-emerald-800
                                                @elseif($submission->verification_status === \BrandedHosting\Platform\Enums\VerificationStatus::Rejected) bg-red-950 text-red-300 border border-red-800
                                                @else bg-amber-950 text-amber-300 border border-amber-800 @endif">
                                                {{ $submission->verification_status->getLabel() }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-1">Submitted: {{ $submission->created_at->format('M d, Y • h:i A') }}</div>
                                        @if($submission->staff_decision_notes)
                                            <div class="text-xs text-amber-300 mt-2 bg-amber-950/40 p-2 rounded border border-amber-900">
                                                Staff Note: {{ $submission->staff_decision_notes }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($submission->proofs->count() > 0)
                                        <a href="{{ $submission->proofs->first()->getDownloadUrl() }}" target="_blank" class="text-xs text-brand-400 hover:underline flex items-center gap-1">
                                            <i class="ti ti-file-text"></i> View Proof Slip
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Order Specs Sidebar -->
            <div class="space-y-6">
                <div class="p-6 rounded-2xl bg-gray-900 border border-gray-800">
                    <h3 class="text-base font-bold text-white mb-4">Allocated Specifications</h3>
                    
                    <ul class="space-y-3 text-xs text-gray-300">
                        <li class="flex justify-between py-1 border-b border-gray-800">
                            <span class="text-gray-400">RAM:</span>
                            <span class="font-bold text-white">{{ round(($order->plan_snapshot['memory_mb'] ?? 1024) / 1024, 1) }} GB</span>
                        </li>
                        <li class="flex justify-between py-1 border-b border-gray-800">
                            <span class="text-gray-400">vCPU Limit:</span>
                            <span class="font-bold text-white">{{ $order->plan_snapshot['cpu_percent'] ?? 100 }}%</span>
                        </li>
                        <li class="flex justify-between py-1 border-b border-gray-800">
                            <span class="text-gray-400">NVMe Storage:</span>
                            <span class="font-bold text-white">{{ round(($order->plan_snapshot['disk_mb'] ?? 10240) / 1024, 1) }} GB</span>
                        </li>
                        <li class="flex justify-between py-1 border-b border-gray-800">
                            <span class="text-gray-400">Databases:</span>
                            <span class="font-bold text-white">{{ $order->plan_snapshot['database_limit'] ?? 1 }}</span>
                        </li>
                        <li class="flex justify-between py-1 border-b border-gray-800">
                            <span class="text-gray-400">Snapshots/Backups:</span>
                            <span class="font-bold text-white">{{ $order->plan_snapshot['backup_limit'] ?? 2 }}</span>
                        </li>
                    </ul>

                    @if($order->server)
                        <div class="mt-6 pt-6 border-t border-gray-800">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-2">Live Node Details</h4>
                            <div class="text-xs text-gray-300 space-y-1">
                                <div>Node: <strong class="text-white">{{ $order->server->node->name }}</strong></div>
                                <div>Address: <span class="font-mono text-brand-300">{{ $order->server->allocation->display_address ?? 'Configuring...' }}</span></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
