@extends('hosting-platform::storefront.layout')

@section('title', 'Terms of Service — STX Enterprises Sri Lanka')

@section('content')
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span class="text-cyan-optic">Terms of Service</span>
        </nav>
        <div class="stx-label text-cyan-optic mb-1.5">Legal Contract & AUP</div>
        <h1 class="stx-display text-4xl sm:text-5xl text-white">Terms of Service</h1>
        <p class="text-xs font-mono text-gray-500 mt-2">Last Updated: October 30, 2026 • STX Enterprises Lanka (Pvt) Ltd</p>
    </div>
</section>

<section class="py-16 bg-[#080B10]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="stx-panel p-8 sm:p-12 border-[#243047] bg-[#0C1019] space-y-8 text-xs text-gray-300 leading-relaxed font-sans">
            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">1. Agreement to Terms</h2>
                <p>
                    By accessing our storefront, creating an account, or subscribing to any server hosting plan provided by STX Enterprises Lanka (Pvt) Ltd ("STX", "we", "us", or "our"), you agree to be bound by these Terms of Service. If you do not agree, you must not use our services.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">2. Acceptable Use Policy (AUP)</h2>
                <p>
                    Our hosting infrastructure is designed for game servers, voice/communication applications, bot runtimes, and legitimate web services. You explicitly agree NOT to use our servers for:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-gray-400">
                    <li>Launching, participating in, or relaying Denial of Service (DoS / DDoS) attacks or port scanning activities.</li>
                    <li>Cryptocurrency mining (e.g. Monero, Bitcoin) or unauthorized background computational hashing without written authorization.</li>
                    <li>Hosting or distributing malware, trojans, ransomware, or phishing websites.</li>
                    <li>Hosting copyrighted materials without proper licenses or distribution rights.</li>
                </ul>
                <p class="text-xs text-red-400 font-mono">
                    Violation of our Acceptable Use Policy will result in immediate service termination without refund.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">3. Billing, Currency & Payments</h2>
                <p>
                    All recurring and one-time service fees are billed in Sri Lankan Rupees (LKR). For manual payment rails (Direct Bank Transfer via Commercial Bank, Sampath Bank, BOC, HNB, or Dialog eZ Cash / Helakuru HelaPay), you agree to provide an authentic, legible transaction slip or digital receipt.
                </p>
                <p>
                    Service provisioning occurs automatically upon verification of funds by our billing desk or automated payment gateway.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">4. Service Level Agreement (SLA) & Uptime Guarantee</h2>
                <p>
                    STX Enterprises targets a minimum of 99.9% monthly network and hardware availability across our Colombo and Singapore compute nodes. Planned maintenance windows are broadcasted in advance via our Public Status Page (<a href="{{ route('hosting.status.index') }}" class="text-cyan-optic underline font-mono">/status</a>).
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">5. Backup Policy & Data Responsibility</h2>
                <p>
                    While STX provides automated backup snapshots and resilient NVMe RAID hardware, you remain solely responsible for maintaining external off-site copies of critical server configurations, databases, and custom game world saves.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">6. Governing Law</h2>
                <p>
                    These terms shall be governed by and construed in accordance with the laws of the Democratic Socialist Republic of Sri Lanka. Any disputes arising out of these terms shall be subject to the exclusive jurisdiction of the courts of Colombo.
                </p>
            </section>
        </div>
    </div>
</section>
@endsection