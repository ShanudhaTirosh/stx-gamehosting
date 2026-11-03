@extends('hosting-platform::storefront.layout')

@section('title', 'Privacy Policy — STX Enterprises Sri Lanka')

@section('content')
<section class="border-b border-[#1D263B] bg-[#07090E] py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-gray-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('hosting.storefront.index') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <span class="text-cyan-optic">Privacy Policy</span>
        </nav>
        <div class="stx-label text-cyan-optic mb-1.5">Data Protection & Transparency</div>
        <h1 class="stx-display text-4xl sm:text-5xl text-white">Privacy Policy</h1>
        <p class="text-xs font-mono text-gray-500 mt-2">Effective Date: October 30, 2026 • STX Enterprises Lanka (Pvt) Ltd</p>
    </div>
</section>

<section class="py-16 bg-[#080B10]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="stx-panel p-8 sm:p-12 border-[#243047] bg-[#0C1019] space-y-8 text-xs text-gray-300 leading-relaxed font-sans">
            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">1. Information We Collect</h2>
                <p>
                    When you use STX Enterprises to deploy servers or order hosting products, we collect minimal personal information necessary to deliver and manage your services:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-gray-400">
                    <li><strong class="text-gray-200">Account Details:</strong> Full name, username, email address, and IP address used during registration and login.</li>
                    <li><strong class="text-gray-200">Order & Billing Data:</strong> Selected hosting tiers, billing period, transaction references, and payment proof images or PDF receipts uploaded for manual verification.</li>
                    <li><strong class="text-gray-200">Telemetry & Technical Logs:</strong> Container resource utilization (RAM, CPU, disk metrics), crash events, and security access logs for DDoS mitigation.</li>
                </ul>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">2. How We Use Your Information</h2>
                <p>
                    Your data is strictly utilized to:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-gray-400">
                    <li>Instantly provision and maintain your containerized game servers.</li>
                    <li>Verify and audit bank slips and digital payment transfers.</li>
                    <li>Dispatch critical maintenance notifications, server credentials, and invoices.</li>
                    <li>Detect and mitigate network intrusions, DDoS attacks, and malicious exploits.</li>
                </ul>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">3. Zero Third-Party Selling Policy</h2>
                <p>
                    STX Enterprises never sells, rents, leases, or trades customer data to third-party advertisers, data brokers, or marketing networks. All billing information is retained securely within encrypted storage environments in compliance with the Personal Data Protection Act No. 9 of 2022 of Sri Lanka.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">4. Payment Slip & Upload Security</h2>
                <p>
                    Payment proof files uploaded via our checkout portal (e.g., bank transfer slips, mobile wallet transaction screenshots) are stored in private, access-controlled storage discs. They are accessible exclusively by authorized billing personnel for verification purposes and are automatically purged in accordance with our data retention schedule.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">5. Cookies & Session Management</h2>
                <p>
                    Our web application uses essential HTTP cookies and Redis in-memory session tokens to remember your login state, keep your shopping cart synchronized during checkout, and enforce CSRF security protections. We do not use third-party tracking or behavioral profiling cookies.
                </p>
            </section>

            <section class="space-y-2">
                <h2 class="text-base font-bold text-white uppercase tracking-wider font-mono">6. Contact Our Privacy Officer</h2>
                <p>
                    If you have questions regarding this Privacy Policy, or wish to request data export or account closure, please email our Privacy and Security desk at <a href="mailto:noc@stxenterprises.lk" class="text-cyan-optic underline font-mono">noc@stxenterprises.lk</a>.
                </p>
            </section>
        </div>
    </div>
</section>
@endsection