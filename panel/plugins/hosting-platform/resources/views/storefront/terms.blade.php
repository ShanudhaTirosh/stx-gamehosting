@extends('hosting-platform::storefront.layout')

@section('title', 'Terms of Service — STX Enterprises')

@section('content')
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-12 rounded-3xl bg-surface border border-white/10 shadow-2xl space-y-8">
            <div class="border-b border-white/10 pb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-400">Legal Agreement</span>
                <h1 class="text-3xl sm:text-4xl font-black text-white mt-1">Terms of Service</h1>
                <p class="text-xs text-gray-400 mt-2">Last Updated: October 30, 2026 • STX Enterprises Lanka (Pvt) Ltd</p>
            </div>

            <div class="space-y-6 text-sm text-gray-300 leading-relaxed">
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">1. Agreement to Terms</h2>
                    <p>
                        By accessing our storefront, creating an account, or subscribing to any server hosting plan provided by STX Enterprises Lanka (Pvt) Ltd ("STX", "we", "us", or "our"), you agree to be bound by these Terms of Service. If you do not agree, you must not use our services.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">2. Acceptable Use Policy (AUP)</h2>
                    <p>
                        Our hosting infrastructure is designed for game servers, voice/communication applications, bot runtimes, and legitimate web services. You explicitly agree NOT to use our servers for:
                    </p>
                    <ul class="list-disc pl-6 space-y-1 text-xs text-gray-400">
                        <li>Launching, participating in, or relaying Denial of Service (DoS / DDoS) attacks or port scanning activities.</li>
                        <li>Cryptocurrency mining (e.g. Monero, Bitcoin) or unauthorized background computational hashing without written authorization.</li>
                        <li>Hosting or distributing malware, trojans, ransomware, or phishing websites.</li>
                        <li>Hosting copyrighted materials without proper licenses or distribution rights.</li>
                    </ul>
                    <p class="text-xs text-rose-300">
                        Violation of our Acceptable Use Policy will result in immediate service termination without refund.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">3. Billing, Currency & Payments</h2>
                    <p>
                        All recurring and one-time service fees are billed in Sri Lankan Rupees (LKR). For manual payment rails (Direct Bank Transfer via Commercial Bank, Sampath Bank, BOC, HNB, or Dialog eZ Cash / Helakuru HelaPay), you agree to provide an authentic, legible transaction slip or digital receipt.
                    </p>
                    <p>
                        Service provisioning occurs automatically upon verification of funds by our billing desk or automated payment gateway.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">4. Service Level Agreement (SLA) & Uptime Guarantee</h2>
                    <p>
                        STX Enterprises targets a minimum of 99.9% monthly network and hardware availability across our Colombo and Singapore compute nodes. Planned maintenance windows are broadcasted in advance via our Public Status Page (<a href="{{ route('hosting.status.index') }}" class="text-indigo-400 underline">/status</a>).
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">5. Backup Policy & Data Responsibility</h2>
                    <p>
                        While STX provides automated backup snapshots and resilient NVMe RAID hardware, you remain solely responsible for maintaining external off-site copies of critical server configurations, databases, and custom game world saves.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">6. Governing Law</h2>
                    <p>
                        These terms shall be governed by and construed in accordance with the laws of the Democratic Socialist Republic of Sri Lanka. Any disputes arising out of these terms shall be subject to the exclusive jurisdiction of the courts of Colombo.
                    </p>
                </section>
            </div>
        </div>
    </div>
</section>
@endsection
