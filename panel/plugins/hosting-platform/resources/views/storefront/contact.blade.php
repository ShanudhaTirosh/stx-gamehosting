@extends('hosting-platform::storefront.layout')

@section('title', 'Contact Us & NOC Helpdesk — STX Enterprises')
@section('meta_description', 'Contact STX Enterprises 24/7 technical support and NOC helpdesk in Colombo. Get in touch via Discord, WhatsApp, or direct inquiry ticketing.')

@section('content')
<!-- Hero Section -->
<section class="relative pt-24 pb-16 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/25 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>24/7/365 NOC HELPDESK & SUPPORT</span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight">
            We’re Here to Help. <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-300">
                Get in Touch with Our Colombo NOC Team.
            </span>
        </h1>
        <p class="mt-4 text-base text-gray-400 max-w-2xl mx-auto">
            Have questions about server plans, custom BGP routing, payment slip verification, or technical plugin setup? Our local engineering team is on standby 24/7.
        </p>
    </div>
</section>

<!-- Content Grid: Form + Info Channels -->
<section class="pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Interactive Contact Form (7 cols) -->
            <div class="lg:col-span-7">
                <div class="p-8 sm:p-10 rounded-3xl bg-surface border border-white/10 shadow-2xl relative">
                    <h2 class="text-2xl font-bold text-white mb-1">Send a Dispatch Request</h2>
                    <p class="text-xs text-gray-400 mb-8">Fill out the form below. We dispatch inquiries directly to the on-call engineer.</p>

                    @if(session('contact_success'))
                        <div class="mb-8 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-start gap-3">
                            <i class="ti ti-circle-check text-xl mt-0.5 shrink-0 text-emerald-400"></i>
                            <div>
                                <strong class="block font-bold">Dispatch Message Sent!</strong>
                                <span class="text-xs leading-relaxed text-emerald-200">{{ session('contact_success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-8 p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm">
                            <strong class="block font-bold mb-1">Please correct the following errors:</strong>
                            <ul class="list-disc pl-5 text-xs space-y-1 text-rose-200">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hosting.storefront.contact.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Your Full Name <span class="text-rose-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    required
                                    value="{{ old('name', auth()->user()?->name) }}"
                                    placeholder="Kasun Perera"
                                    class="w-full px-4 py-3 rounded-xl bg-obsidian border border-white/10 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                                >
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                    Email Address <span class="text-rose-400">*</span>
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    required
                                    value="{{ old('email', auth()->user()?->email) }}"
                                    placeholder="kasun@example.com"
                                    class="w-full px-4 py-3 rounded-xl bg-obsidian border border-white/10 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                                >
                            </div>
                        </div>

                        <!-- Department Selection -->
                        <div>
                            <label for="department" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Target Department <span class="text-rose-400">*</span>
                            </label>
                            <select
                                name="department"
                                id="department"
                                required
                                class="w-full px-4 py-3 rounded-xl bg-obsidian border border-white/10 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                            >
                                <option value="technical" {{ old('department') == 'technical' ? 'selected' : '' }}>Technical Support & Server Configuration</option>
                                <option value="billing" {{ old('department') == 'billing' ? 'selected' : '' }}>Billing, Invoicing & Bank Slips</option>
                                <option value="sales" {{ old('department') == 'sales' ? 'selected' : '' }}>Sales & Custom Enterprise Quotes</option>
                                <option value="abuse" {{ old('department') == 'abuse' ? 'selected' : '' }}>Network Security, DDoS & Abuse Report</option>
                            </select>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Subject <span class="text-rose-400">*</span>
                            </label>
                            <input
                                type="text"
                                name="subject"
                                id="subject"
                                required
                                value="{{ old('subject') }}"
                                placeholder="e.g. Inquiry regarding 8GB Paper Minecraft custom plugins"
                                class="w-full px-4 py-3 rounded-xl bg-obsidian border border-white/10 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                            >
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                                Detailed Message <span class="text-rose-400">*</span>
                            </label>
                            <textarea
                                name="message"
                                id="message"
                                rows="5"
                                required
                                placeholder="Describe your question, server requirements, or issue in detail..."
                                class="w-full px-4 py-3 rounded-xl bg-obsidian border border-white/10 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition leading-relaxed"
                            >{{ old('message') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full py-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2"
                        >
                            <i class="ti ti-send text-base"></i>
                            <span>Transmit Dispatch Ticket</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Direct Channels & Hotline (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Direct Support Channels -->
                <div class="p-8 rounded-3xl bg-surface border border-white/10 space-y-6">
                    <h3 class="text-xl font-bold text-white">Direct Communication Hub</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">Prefer real-time chat or immediate voice contact? Connect with our engineers directly.</p>

                    <!-- Channel 1: Email -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shrink-0">
                            <i class="ti ti-mail"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-gray-400">Official Invoicing & Desk</div>
                            <a href="mailto:billing@stxenterprises.lk" class="text-sm font-bold text-white hover:text-indigo-400 transition font-mono truncate block">
                                billing@stxenterprises.lk
                            </a>
                            <div class="text-[11px] text-gray-500 mt-0.5">Average reply time: ~15 mins</div>
                        </div>
                    </div>

                    <!-- Channel 2: Discord -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xl shrink-0">
                            <i class="ti ti-brand-discord"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-gray-400">Gamer Community & Live Bot Support</div>
                            <a href="https://discord.gg" target="_blank" class="text-sm font-bold text-white hover:text-indigo-400 transition flex items-center gap-1.5 mt-0.5">
                                <span>STX Gaming Discord</span>
                                <i class="ti ti-external-link text-xs"></i>
                            </a>
                            <div class="text-[11px] text-emerald-400 mt-0.5">● 1,500+ Sri Lankan Members Online</div>
                        </div>
                    </div>

                    <!-- Channel 3: Phone / WhatsApp -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                            <i class="ti ti-phone-call"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-gray-400">Sri Lanka Operations Hotline</div>
                            <div class="text-sm font-bold text-white font-mono mt-0.5">+94 11 234 5678</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">Monday – Sunday (24/7 Priority Emergency)</div>
                        </div>
                    </div>

                    <!-- Channel 4: Address -->
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl shrink-0">
                            <i class="ti ti-map-pin"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-gray-400">Headquarters</div>
                            <div class="text-sm font-bold text-white mt-0.5">STX Enterprises Lanka (Pvt) Ltd</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">Galle Road, Colombo 03, Western Province, Sri Lanka</div>
                        </div>
                    </div>
                </div>

                <!-- Live Status Quick Pill -->
                <div class="p-6 rounded-3xl bg-surface border border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></div>
                        <div>
                            <strong class="text-sm text-white block">Cluster Network Status</strong>
                            <span class="text-xs text-gray-400">All nodes & payment rails operational</span>
                        </div>
                    </div>
                    <a href="{{ route('hosting.status.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-emerald-300 bg-emerald-500/15 border border-emerald-500/30 hover:bg-emerald-500/25 transition">
                        View Telemetry
                    </a>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-24 max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-white">Frequently Asked Questions</h2>
                <p class="text-sm text-gray-400 mt-2">Quick answers to common questions about our platform and billing.</p>
            </div>

            <div class="space-y-4">
                <details class="p-6 rounded-2xl bg-surface border border-white/5 group">
                    <summary class="font-bold text-white text-sm cursor-pointer list-none flex items-center justify-between">
                        <span>How fast is my server provisioned after paying?</span>
                        <i class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        If you upload your payment slip (eZ Cash / HelaPay / Bank CDM receipt), our automated verification verifies the transaction and immediately provisions your container within 1 to 10 minutes. You will receive an SMS and email notification with your server IP and credentials.
                    </p>
                </details>

                <details class="p-6 rounded-2xl bg-surface border border-white/5 group">
                    <summary class="font-bold text-white text-sm cursor-pointer list-none flex items-center justify-between">
                        <span>Can I change Minecraft versions (e.g. Paper to Fabric)?</span>
                        <i class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        Yes! All Minecraft plans include our instant version changer and full Egg selector. You can toggle between Paper, Purpur, Fabric, Forge, and Bedrock with a single click, or upload your own custom server JAR file via SFTP.
                    </p>
                </details>

                <details class="p-6 rounded-2xl bg-surface border border-white/5 group">
                    <summary class="font-bold text-white text-sm cursor-pointer list-none flex items-center justify-between">
                        <span>What happens if my server runs out of RAM?</span>
                        <i class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        You can upgrade your plan at any time through the customer dashboard. Upgrades are seamless and preserve all world files, databases, and plugin configurations without data loss.
                    </p>
                </details>
            </div>
        </div>
    </div>
</section>
@endsection
