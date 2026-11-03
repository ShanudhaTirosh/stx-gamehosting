@extends('hosting-platform::storefront.layout')

@php
    $seo = \BrandedHosting\Platform\Models\HostingSeoSetting::current();
@endphp

@section('title', 'NOC Dispatch & Customer Helpdesk — STX Enterprises Colombo')
@section('meta_description', 'Contact STX Enterprises 24/7 technical support and NOC helpdesk in Colombo. Get in touch via Discord, WhatsApp, or direct inquiry ticketing.')

@section('content')
<!-- Header Section -->
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span class="text-cyan-optic">NOC Helpdesk & Contact</span>
        </nav>

        <h1 class="stx-display text-4xl sm:text-6xl text-white tracking-tight">
            Colombo NOC Dispatch & Helpdesk
        </h1>
        <p class="mt-4 text-base sm:text-lg text-gray-400 max-w-3xl leading-relaxed">
            Have questions about server provisioning, custom BGP routing, slip verification, or technical plugin setup? Our Colombo systems team is on standby 24/7.
        </p>

        <div class="mt-6 flex flex-wrap items-center gap-4 text-xs font-mono text-gray-400">
            <span class="flex items-center gap-1.5 text-emerald-400"><span class="stx-dot bg-emerald-400"></span> On-Call Engineers Active</span>
            <span>•</span>
            <span>Average Dispatch Reply: &lt; 15 mins</span>
            <span>•</span>
            <span>24/7 Islandwide Assistance</span>
        </div>
    </div>
</section>

<!-- Content Grid: Form + Info Channels -->
<section class="py-16 bg-[#080B10]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left Column: Dispatch Form (7 cols) -->
            <div class="lg:col-span-7">
                <div class="stx-panel p-8 sm:p-10 border-[#243047] bg-[#0C1019] space-y-6">
                    <div class="border-b border-[#1D263B] pb-4">
                        <h2 class="text-xl font-bold text-white">Send a Dispatch Request</h2>
                        <p class="text-xs text-gray-400 mt-1">Submit your inquiry directly to our on-call engineering or billing team.</p>
                    </div>

                    @if(session('contact_success'))
                        <div class="p-4 rounded-[2px] bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-xs flex items-start gap-3 font-mono">
                            <i class="ti ti-circle-check text-base mt-0.5 shrink-0 text-emerald-400"></i>
                            <div>
                                <strong class="block font-bold">Dispatch Message Transmitted</strong>
                                <span class="text-gray-300">{{ session('contact_success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 rounded-[2px] bg-red-950/40 border border-red-500/30 text-red-300 text-xs font-mono">
                            <strong class="block font-bold mb-1">Please correct the following:</strong>
                            <ul class="list-disc pl-5 space-y-0.5 text-gray-300">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hosting.storefront.contact.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="stx-label block mb-1.5">
                                    Full Name <span class="text-cyan-optic">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    required
                                    value="{{ old('name', auth()->user()?->name) }}"
                                    placeholder="e.g. Kasun Perera"
                                    class="stx-input"
                                >
                            </div>

                            <div>
                                <label for="email" class="stx-label block mb-1.5">
                                    Email Address <span class="text-cyan-optic">*</span>
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    required
                                    value="{{ old('email', auth()->user()?->email) }}"
                                    placeholder="kasun@example.lk"
                                    class="stx-input"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="department" class="stx-label block mb-1.5">
                                Target Department <span class="text-cyan-optic">*</span>
                            </label>
                            <select
                                name="department"
                                id="department"
                                required
                                class="stx-input !py-2.5"
                            >
                                <option value="technical" {{ old('department') == 'technical' ? 'selected' : '' }}>Technical Support & Server Configuration</option>
                                <option value="billing" {{ old('department') == 'billing' ? 'selected' : '' }}>Billing, Invoicing & Bank Slips</option>
                                <option value="sales" {{ old('department') == 'sales' ? 'selected' : '' }}>Sales & Custom Enterprise Quotes</option>
                                <option value="abuse" {{ old('department') == 'abuse' ? 'selected' : '' }}>Network Security, DDoS & Abuse Report</option>
                            </select>
                        </div>

                        <div>
                            <label for="subject" class="stx-label block mb-1.5">
                                Inquiry Subject <span class="text-cyan-optic">*</span>
                            </label>
                            <input
                                type="text"
                                name="subject"
                                id="subject"
                                required
                                value="{{ old('subject') }}"
                                placeholder="e.g. Question regarding 8GB Minecraft Paper allocation"
                                class="stx-input"
                            >
                        </div>

                        <div>
                            <label for="message" class="stx-label block mb-1.5">
                                Detailed Message <span class="text-cyan-optic">*</span>
                            </label>
                            <textarea
                                name="message"
                                id="message"
                                rows="5"
                                required
                                placeholder="Detail your server specs, player count, or transaction reference..."
                                class="stx-input leading-relaxed"
                            >{{ old('message') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full stx-btn-cyan !py-3.5 text-xs font-mono uppercase tracking-wider"
                        >
                            <i class="ti ti-send text-sm"></i> Transmit Dispatch Ticket
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Direct Channels & Hotline (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="stx-panel p-8 border-[#243047] bg-[#0C1019] space-y-6">
                    <div class="border-b border-[#1D263B] pb-4">
                        <h3 class="text-lg font-bold text-white">Direct Communication Hub</h3>
                        <p class="text-xs text-gray-400 mt-1">Prefer direct voice or chat? Reach our Colombo operations team.</p>
                    </div>

                    <!-- Channel: Email -->
                    <div class="flex items-start gap-3.5 p-3.5 rounded-[2px] bg-[#07090E] border border-[#182133]">
                        <div class="w-9 h-9 rounded-[2px] bg-[#0E131E] text-cyan-optic flex items-center justify-center text-lg shrink-0 border border-[#212B3E]">
                            <i class="ti ti-mail"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stx-label text-[10px]">Official Invoicing & Desk</div>
                            <a href="mailto:noc@stxenterprises.lk" class="text-sm font-bold text-white hover:text-cyan-optic transition font-mono truncate block mt-0.5">
                                noc@stxenterprises.lk
                            </a>
                            <div class="text-[11px] text-gray-500 font-mono mt-0.5">Average reply: ~15 mins</div>
                        </div>
                    </div>

                    <!-- Channel: Discord -->
                    <div class="flex items-start gap-3.5 p-3.5 rounded-[2px] bg-[#07090E] border border-[#182133]">
                        <div class="w-9 h-9 rounded-[2px] bg-[#0E131E] text-indigo-400 flex items-center justify-center text-lg shrink-0 border border-[#212B3E]">
                            <i class="ti ti-brand-discord"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stx-label text-[10px]">Community & Live Bot Hub</div>
                            <a href="{{ $seo->support_discord_url ?: config('app.discord_url', 'https://discord.gg/shanutechx') }}" target="_blank" class="text-sm font-bold text-white hover:text-cyan-optic transition flex items-center gap-1.5 font-mono mt-0.5">
                                <span>STX Discord Server</span>
                                <i class="ti ti-external-link text-xs"></i>
                            </a>
                            <div class="text-[11px] text-emerald-400 font-mono mt-0.5">● 1,500+ Active Sri Lankan Members</div>
                        </div>
                    </div>

                    <!-- Channel: Phone / WhatsApp -->
                    <div class="flex items-start gap-3.5 p-3.5 rounded-[2px] bg-[#07090E] border border-[#182133]">
                        <div class="w-9 h-9 rounded-[2px] bg-[#0E131E] text-emerald-400 flex items-center justify-center text-lg shrink-0 border border-[#212B3E]">
                            <i class="ti ti-phone-call"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stx-label text-[10px]">Operations & Hotline</div>
                            <div class="text-sm font-bold text-white font-mono mt-0.5">+94 11 234 5678</div>
                            <div class="text-[11px] text-gray-500 font-mono mt-0.5">Mon–Sun 24/7 Priority Emergency</div>
                        </div>
                    </div>

                    <!-- Channel: Colombo Office -->
                    <div class="flex items-start gap-3.5 p-3.5 rounded-[2px] bg-[#07090E] border border-[#182133]">
                        <div class="w-9 h-9 rounded-[2px] bg-[#0E131E] text-cyan-optic flex items-center justify-center text-lg shrink-0 border border-[#212B3E]">
                            <i class="ti ti-map-pin"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="stx-label text-[10px]">Colombo Headquarters</div>
                            <div class="text-xs font-bold text-white mt-0.5">STX Enterprises Lanka (Pvt) Ltd</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">Galle Road, Colombo 03, Western Province, Sri Lanka</div>
                        </div>
                    </div>
                </div>

                <!-- Live Status Quick Pill -->
                <div class="stx-panel p-4 border-[#243047] bg-[#0C1019] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="stx-dot bg-emerald-400"></span>
                        <div>
                            <span class="text-xs font-bold text-white block">Cluster Telemetry Nominal</span>
                            <span class="text-[11px] text-gray-500 font-mono">99.98% SLA operational</span>
                        </div>
                    </div>
                    <a href="{{ route('hosting.status.index') }}" class="stx-btn-outline !py-1.5 !px-3 text-xs font-mono">
                        View Status
                    </a>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20 max-w-4xl mx-auto">
            <div class="mb-8">
                <div class="stx-label text-cyan-optic mb-1.5">Common Questions</div>
                <h2 class="stx-display text-2xl sm:text-3xl text-white">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-3">
                <details class="p-5 stx-panel bg-[#0C1019] group cursor-pointer">
                    <summary class="font-bold text-white text-sm list-none flex items-center justify-between">
                        <span>How quickly is my server provisioned after payment?</span>
                        <i class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        When you upload your payment slip (eZ Cash, HelaPay, or Bank CDM slip), our Colombo billing desk verifies the transaction and automatically deploys your server container in under 15 minutes. You will receive an SMS and email notification with your dedicated server IP, port, and SFTP credentials.
                    </p>
                </details>

                <details class="p-5 stx-panel bg-[#0C1019] group cursor-pointer">
                    <summary class="font-bold text-white text-sm list-none flex items-center justify-between">
                        <span>Can I change Minecraft software engines (e.g. Paper to Fabric)?</span>
                        <i class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        Yes! All game server tiers include our integrated Version Changer. You can switch between Paper, Purpur, Fabric, Forge, Spigot, and Bedrock with one click, or upload your own custom JAR files via web SFTP.
                    </p>
                </details>

                <details class="p-5 stx-panel bg-[#0C1019] group cursor-pointer">
                    <summary class="font-bold text-white text-sm list-none flex items-center justify-between">
                        <span>Can I upgrade RAM or CPU if my player base grows?</span>
                        <i class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        Yes. You can upgrade or resize your container tier at any time directly from the client billing panel. Upgrades are seamless and preserve all world saves, MySQL databases, and configuration files.
                    </p>
                </details>
            </div>
        </div>
    </div>
</section>
@endsection