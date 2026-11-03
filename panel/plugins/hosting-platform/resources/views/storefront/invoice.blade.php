<!DOCTYPE html>
@php
    $seo = $seo ?? \BrandedHosting\Platform\Models\HostingSeoSetting::current();
    $snap = $order->plan_snapshot ?? [];
    $setupFee = (float) ($snap['setup_fee_lkr'] ?? 0);
    $planPrice = (float) ($snap['price_lkr'] ?? max(0, (float) $order->subtotal_lkr - $setupFee));
    $discount = (float) $order->discount_lkr;
    $total = (float) $order->total_amount_lkr;
    $paid = in_array($order->status->value, ['approved', 'provisioning', 'completed'], true);
    $dead = in_array($order->status->value, ['cancelled', 'expired'], true);
    $statusText = $paid ? 'Paid' : ($dead ? ucfirst($order->status->value) : 'Awaiting payment');
    $money = fn ($n) => 'Rs. ' . number_format((float) $n, 2);
    $company = ($seo->company_legal_name ?? null) ?: (($seo->site_name ?? null) ?: 'STX Enterprises');
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Invoice {{ $order->order_number }} - {{ $company }}</title>
    <style>
        :root { --ink:#15171A; --mut:#6B7280; --line:#D9DCE1; --acc:#15171A; --ok:#117A3C; --warn:#9A6200; --bad:#B42318; }
        * { box-sizing: border-box; }
        body { margin:0; background:#F2F3F5; color:var(--ink); font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif; font-size:14px; line-height:1.5; }
        .sheet { max-width:820px; margin:32px auto; background:#fff; border:1px solid var(--line); padding:48px; }
        .row { display:flex; justify-content:space-between; gap:24px; flex-wrap:wrap; }
        h1 { margin:0; font-size:34px; letter-spacing:.04em; text-transform:uppercase; }
        .label { font-size:11px; letter-spacing:.1em; text-transform:uppercase; color:var(--mut); }
        .mono { font-family: ui-monospace, "SF Mono", Menlo, Consolas, monospace; }
        .status { display:inline-block; padding:2px 10px; border:1px solid currentColor; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.06em; }
        table { width:100%; border-collapse:collapse; margin-top:32px; }
        th { text-align:left; font-size:11px; letter-spacing:.1em; text-transform:uppercase; color:var(--mut); font-weight:500; padding:8px 0; border-bottom:2px solid var(--ink); }
        td { padding:14px 0; border-bottom:1px solid var(--line); vertical-align:top; }
        .r { text-align:right; }
        .totals { margin-left:auto; width:300px; margin-top:16px; }
        .totals div { display:flex; justify-content:space-between; padding:6px 0; }
        .totals .grand { border-top:2px solid var(--ink); margin-top:6px; padding-top:12px; font-size:18px; font-weight:700; }
        .meta { margin-top:36px; display:grid; grid-template-columns:1fr 1fr; gap:24px; }
        .foot { margin-top:48px; padding-top:16px; border-top:1px solid var(--line); color:var(--mut); font-size:12px; }
        .actions { max-width:820px; margin:24px auto 0; text-align:right; }
        .actions button { font:inherit; padding:8px 16px; border:1px solid var(--ink); background:var(--ink); color:#fff; cursor:pointer; }
        @media (max-width:640px) { .sheet { padding:24px; margin:0; border:0; } .meta { grid-template-columns:1fr; } }
        @media print { body { background:#fff; } .sheet { border:0; margin:0; padding:0; max-width:none; } .actions { display:none; } }
    </style>
</head>
<body>
<div class="actions"><button type="button" onclick="window.print()">Print or save as PDF</button></div>
<main class="sheet">
    <div class="row">
        <div>
            <h1>Invoice</h1>
            <div class="mono" style="margin-top:6px">{{ $order->order_number }}</div>
        </div>
        <div style="text-align:right">
            <div style="font-weight:700;font-size:16px">{{ $company }}</div>
            @if($seo->contact_email)<div>{{ $seo->contact_email }}</div>@endif
            @if($seo->contact_phone)<div>{{ $seo->contact_phone }}</div>@endif
        </div>
    </div>

    <div class="meta">
        <div>
            <div class="label">Billed to</div>
            <div style="font-weight:600">{{ $order->user->username }}</div>
            <div>{{ $order->user->email }}</div>
        </div>
        <div>
            <div class="label">Issued</div>
            <div>{{ $order->created_at->format('j F Y') }}</div>
            <div class="label" style="margin-top:12px">Status</div>
            <span class="status" style="color:{{ $paid ? 'var(--ok)' : ($dead ? 'var(--bad)' : 'var(--warn)') }}">{{ $statusText }}</span>
        </div>
    </div>

    <table>
        <thead><tr><th>Description</th><th class="r">Amount</th></tr></thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $snap['plan_name'] ?? ($order->plan->name ?? 'Server plan') }}</strong>
                    @if(!empty($snap['product_name'])) <span style="color:var(--mut)">({{ $snap['product_name'] }})</span>@endif
                    @if(!empty($snap['billing_period']))<div style="color:var(--mut)">Billing period: {{ ucfirst(str_replace('_', ' ', $snap['billing_period'])) }}</div>@endif
                    <div class="mono" style="color:var(--mut);font-size:12px">
                        {{ rtrim(rtrim(number_format(($snap['memory_mb'] ?? 0) / 1024, 1), '0'), '.') }} GB RAM
                        / {{ $snap['cpu_percent'] ?? 0 }}% CPU
                        / {{ rtrim(rtrim(number_format(($snap['disk_mb'] ?? 0) / 1024, 1), '0'), '.') }} GB disk
                    </div>
                    @if(!empty($order->server_configuration['requested_name']))<div style="color:var(--mut)">Server name: {{ $order->server_configuration['requested_name'] }}</div>@endif
                </td>
                <td class="r mono">{{ $money($planPrice) }}</td>
            </tr>
            @if($setupFee > 0)
            <tr><td>Setup fee</td><td class="r mono">{{ $money($setupFee) }}</td></tr>
            @endif
        </tbody>
    </table>

    <div class="totals mono">
        <div><span>Subtotal</span><span>{{ $money($order->subtotal_lkr) }}</span></div>
        @if($discount > 0)<div><span>Discount</span><span>- {{ $money($discount) }}</span></div>@endif
        <div class="grand"><span>Total (LKR)</span><span>{{ $money($total) }}</span></div>
    </div>

    <div class="meta">
        <div>
            <div class="label">Payment method</div>
            <div>{{ $order->paymentMethod->name ?? 'Not selected' }}</div>
        </div>
        <div>
            <div class="label">Order reference</div>
            <div class="mono">{{ $order->order_number }}</div>
        </div>
    </div>

    <div class="foot">
        {{ $paid ? 'Payment received and verified. Thank you.' : 'To pay, follow the instructions on your order page and upload your payment slip. We deploy your server after we verify the payment.' }}
        Billing questions: {{ $seo->contact_email ?: 'use the contact form on our website' }}.
    </div>
</main>
</body>
</html>
