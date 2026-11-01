@extends('hosting-platform::storefront.layout')

@section('title', 'Privacy Policy — STX Enterprises')

@section('content')
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-12 rounded-3xl bg-surface border border-white/10 shadow-2xl space-y-8">
            <div class="border-b border-white/10 pb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Data Protection & Transparency</span>
                <h1 class="text-3xl sm:text-4xl font-black text-white mt-1">Privacy Policy</h1>
                <p class="text-xs text-gray-400 mt-2">Effective Date: October 30, 2026 • STX Enterprises Lanka (Pvt) Ltd</p>
            </div>

            <div class="space-y-6 text-sm text-gray-300 leading-relaxed">
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">1. Information We Collect</h2>
                    <p>
                        When you use STX Enterprises to deploy servers or order hosting products, we collect minimal personal information necessary to deliver and manage your services:
                    </p>
                    <ul class="list-disc pl-6 space-y-1 text-xs text-gray-400">
                        <li><strong>Account Details:</strong> Full name, username, email address, and IP address used during registration and login.</li>
                        <li><strong>Order & Billing Data:</strong> Selected hosting tiers, billing period, transaction references, and payment proof images or PDF receipts uploaded for manual verification.</li>
                        <li><strong>Telemetry & Technical Logs:</strong> Container resource utilization (RAM, CPU, disk metrics), crash events, and security access logs for DDoS mitigation.</li>
                    </ul>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">2. How We Use Your Information</h2>
                    <p>
                        Your data is strictly utilized to:
                    </p>
                    <ul class="list-disc pl-6 space-y-1 text-xs text-gray-400">
                        <li>Instantly provision and maintain your containerized game servers.</li>
                        <li>Verify and audit bank slips and digital payment transfers.</li>
                        <li>Dispatch critical maintenance notifications, server credentials, and invoices.</li>
                        <li>Detect and mitigate network intrusions, DDoS attacks, and malicious exploits.</li>
                    </ul>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">3. Zero Third-Party Selling Policy</h2>
                    <p>
                        STX Enterprises never sells, rents, leases, or trades customer data to third-party advertisers, data brokers, or marketing networks. All billing information is retained securely within encrypted storage environments in compliance with the Personal Data Protection Act No. 9 of 2022 of Sri Lanka.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">4. Payment Slip & Upload Security</h2>
                    <p>
                        Payment proof files uploaded via our checkout portal (e.g., bank transfer slips, mobile wallet transaction screenshots) are stored in private, access-controlled storage discs. They are accessible exclusively by authorized billing personnel for verification purposes and are automatically purged in accordance with our data retention schedule.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">5. Cookies & Session Management</h2>
                    <p>
                        Our web application uses essential HTTP cookies and Redis in-memory session tokens to remember your login state, keep your shopping cart synchronized during checkout, and enforce CSRF security protections. We do not use third-party tracking or behavioral profiling cookies.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-white">6. Contact Our Privacy Officer</h2>
                    <p>
                        If you have questions regarding this Privacy Policy, or wish to request data export or account closure, please email our Privacy and Security desk at <a href="mailto:billing@stxenterprises.lk" class="text-indigo-400 underline font-mono">billing@stxenterprises.lk</a>.
                    </p>
                </section>
            </div>
        </div>
    </div>
</section>
@endsection
