<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #STX-{{ date('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} — STX Enterprises</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#07060d;
            --paper:#ffffff;
            --line:#111318;
            --teal:#20c6e0;
            --teal-deep:#146a8a;
            --purple:#7146eb;
            --purple-deep:#4a32a8;
            --blue:#4574e1;
            --accent-grad:linear-gradient(115deg, var(--purple) 0%, var(--blue) 52%, var(--teal) 100%);
            --muted:#5b6472;
            --faint:#eef1f4;
            --card:#f7f8fa;
            --danger:#c0392b;
            --success:#10b981;
            --radius:2px;
        }

        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;}
        body{
            background:#1b1e24;
            font-family:'Space Grotesk', sans-serif;
            color:var(--ink);
            padding:40px 16px 80px;
        }

        /* Chrome / nav (screen only) */
        .chrome{
            max-width:840px;
            margin:0 auto 24px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            flex-wrap:wrap;
        }
        .nav-back{
            display:inline-flex;
            align-items:center;
            gap:8px;
            color:#9aa3af;
            text-decoration:none;
            font-size:13px;
            font-weight:600;
            transition:color .15s ease;
        }
        .nav-back:hover{ color:#fff; }
        .printbtn{
            appearance:none;
            border:1px solid var(--teal);
            background:transparent;
            color:var(--teal);
            font-family:'Space Grotesk',sans-serif;
            font-weight:600;
            font-size:12.5px;
            letter-spacing:.06em;
            text-transform:uppercase;
            padding:10px 22px;
            border-radius:999px;
            cursor:pointer;
            transition:all .15s ease;
        }
        .printbtn:hover{ background:var(--teal); color:#06110f; }

        .stage{ max-width:840px; margin:0 auto; }

        .page{
            background:var(--paper);
            width:100%;
            min-height:1020px;
            padding:64px 60px 56px;
            position:relative;
            box-shadow:0 30px 80px rgba(0,0,0,.45);
        }

        /* Brand Header */
        .brand-logo{ display:inline-block; line-height:1; }
        .brand-logo img{ height:42px; display:block; }
        .brand-logo .sub{
            font-family:'Archivo Black', sans-serif;
            font-size:9.5px;
            letter-spacing:.36em;
            color:var(--ink);
            padding-top:6px;
            border-top:2px solid var(--ink);
            margin-top:7px;
        }

        /* Invoice Structure */
        .inv-top{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            margin-bottom:36px;
        }
        .inv-tag{
            text-align:right;
        }
        .inv-tag .label{
            font-family:'Space Grotesk',sans-serif;
            font-weight:700;
            font-size:28px;
            letter-spacing:.06em;
            color:var(--ink);
        }
        .inv-tag .num{
            font-family:'JetBrains Mono', monospace;
            font-size:13px;
            color:var(--purple-deep);
            margin-top:4px;
            font-weight:700;
        }
        .inv-tealbar{
            height:5px;
            background:var(--ink);
            position:relative;
            margin-bottom:34px;
        }
        .inv-tealbar::after{
            content:"";
            position:absolute; left:0; top:0; height:100%;
            width:34%;
            background:var(--accent-grad);
        }
        .inv-meta{
            display:grid;
            grid-template-columns:1.2fr 1fr 1.2fr;
            gap:24px;
            margin-bottom:36px;
        }
        .inv-meta .block .h{
            font-family:'JetBrains Mono', monospace;
            font-size:10px;
            letter-spacing:.14em;
            text-transform:uppercase;
            color:var(--teal-deep);
            font-weight:700;
            margin-bottom:8px;
        }
        .inv-meta .block .l{
            font-size:13px;
            line-height:1.7;
            color:#20242b;
        }
        .inv-meta .block .l strong{ display:block; font-size:14px; }

        table.items{
            width:100%;
            border-collapse:collapse;
            margin-bottom:8px;
        }
        table.items thead th{
            text-align:left;
            font-family:'JetBrains Mono', monospace;
            font-size:10px;
            letter-spacing:.1em;
            text-transform:uppercase;
            color:#fff;
            background:var(--ink);
            padding:12px 14px;
            font-weight:700;
        }
        table.items thead th:last-child, table.items tbody td:last-child{ text-align:right; }
        table.items thead th.qty, table.items thead th.cycle,
        table.items tbody td.qty, table.items tbody td.cycle{ text-align:center; }
        table.items tbody td{
            padding:14px;
            font-size:13px;
            border-bottom:1px solid var(--faint);
            color:#20242b;
        }
        table.items tbody tr:nth-child(even){ background:var(--card); }
        table.items tbody td.desc strong{ display:block; font-size:13.5px; }
        table.items tbody td.desc span{ color:var(--muted); font-size:11.5px; }

        .inv-totals{
            display:flex;
            justify-content:flex-end;
            margin-top:22px;
        }
        .inv-totals .box{ width:300px; }
        .inv-totals .row{
            display:flex;
            justify-content:space-between;
            font-size:13px;
            padding:8px 0;
            color:var(--muted);
        }
        .inv-totals .row.total{
            border-top:2.5px solid var(--ink);
            margin-top:6px;
            padding-top:14px;
            color:var(--ink);
            font-weight:700;
            font-size:17px;
        }
        .inv-totals .row.total .amt{ font-family:'JetBrains Mono', monospace; color:var(--teal-deep); }

        .inv-bottom{
            display:grid;
            grid-template-columns:1.3fr 1fr;
            gap:36px;
            margin-top:48px;
            padding-top:24px;
            border-top:1px solid #d8dce1;
        }
        .inv-bottom .h{
            font-family:'JetBrains Mono', monospace;
            font-size:10px;
            letter-spacing:.14em;
            text-transform:uppercase;
            color:var(--teal-deep);
            font-weight:700;
            margin-bottom:10px;
        }
        .inv-bottom .l{ font-size:12.5px; line-height:1.85; color:#3a4048; }
        .inv-status{
            display:inline-block;
            margin-top:2px;
            font-family:'JetBrains Mono', monospace;
            font-size:11px;
            letter-spacing:.12em;
            font-weight:700;
            text-transform:uppercase;
            padding:7px 16px;
            border-radius:999px;
        }
        .inv-status.awaiting{
            border:1.5px solid var(--purple-deep);
            color:var(--purple-deep);
            background:rgba(113, 70, 235, 0.05);
        }
        .inv-status.paid{
            border:1.5px solid var(--success);
            color:var(--success);
            background:rgba(16, 185, 129, 0.08);
        }
        .inv-status.review{
            border:1.5px solid var(--blue);
            color:var(--blue);
            background:rgba(69, 116, 225, 0.08);
        }

        /* Print Override */
        @media print{
            body{ background:#fff; padding:0; margin:0; }
            .chrome{ display:none !important; }
            .stage{ max-width:none; }
            .page{ box-shadow:none !important; min-height:0; padding:18mm 16mm; }
            @page{ margin:0; size:A4; }
        }
    </style>
</head>
<body>

    <!-- Header Controls (Screen Only) -->
    <div class="chrome">
        <a href="{{ route('hosting.order.tracking', $order->uuid) }}" class="nav-back">
            &larr; Back to Order Tracking
        </a>
        <button class="printbtn" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <div class="stage">
        <section class="page" id="invoice">
            <!-- Brand & Invoice Header -->
            <div class="inv-top">
                <div class="brand-logo">
                    <img src="data:image/png;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8IS0tIENyZWF0b3I6IENvcmVsRFJBVyAyMDI2IChFdmFsdWF0aW9uIFZlcnNpb24pIC0tPg0KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIxMGluIiBoZWlnaHQ9IjEwaW4iIHZlcnNpb249IjEuMSIgc3R5bGU9InNoYXBlLXJlbmRlcmluZzpnZW9tZXRyaWNQcmVjaXNpb247IHRleHQtcmVuZGVyaW5nOmdlb21ldHJpY1ByZWNpc2lvbjsgaW1hZ2UtcmVuZGVyaW5nOm9wdGltaXplUXVhbGl0eTsgZmlsbC1ydWxlOmV2ZW5vZGQ7IGNsaXAtcnVsZTpldmVub2RkIg0Kdmlld0JveD0iMCAwIDEwMDAwIDEwMDAwIg0KIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIg0KIHhtbG5zOnhvZG09Imh0dHA6Ly93d3cuY29yZWwuY29tL2NvcmVsZHJhdy9vZG0vMjAwMyI+DQogPGRlZnM+DQogIDxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQogICA8IVtDREFUQVsNCiAgICAuc3RyMCB7c3Ryb2tlOiMzNzM0MzU7c3Ryb2tlLXdpZHRoOjYuNjc7c3Ryb2tlLW1pdGVybGltaXQ6Mi42MTMxM30NCiAgICAuZmlsMCB7ZmlsbDpibGFja30NCiAgIF1dPg0KICA8L3N0eWxlPg0KIDwvZGVmcz4NCjxzeW1ib2wgaWQ9IlN5bWJvbDEiIHZpZXdCb3g9IjgzNy42NyAzMzgwIDgzMjQuNjQgMzI0MCI+DQogPHBvbHlnb24gY2xhc3M9ImZpbDAgc3RyMCIgcG9pbnRzPSI4NTAwLDY2MTYuNjcgNzU3Ni42Nyw2NjE2LjY3IDY3NjMuMzMsNTc1Ni42NyA1OTEwLDY2MTYuNjcgNDI5MCw2NjE2LjY3IDQ2NjAsNDUzMCA1MzkwLDQ1MzAgNTAzMCw2NTgwIDUwMzAsNjU5MCA2MzMzLjMzLDUyOTMuMzMgNTYwMy4zMyw0NTMwIDY1MjYuNjcsNDUyNi42NyAiLz4NCiA8cG9seWdvbiBjbGFzcz0iZmlsMCBzdHIwIiBwb2ludHM9IjgxNjYuNjcsMzM4My4zMyA5MTYwLDMzODMuMzMgNzMyMCw1MTU2LjY3IDY4NzMuMzMsNDY3MCAiLz4NCiA8cG9seWdvbiBjbGFzcz0iZmlsMCBzdHIwIiBwb2ludHM9IjIxNDAsNDk2My4zMyAyMDA2LjY3LDQ4MjYuNjcgMTk2My4zMyw0NzUwIDE5NDMuMzMsNDY3Ni42NyAxOTQwLDQ2MTAgMTk0Ni42Nyw0NTYzLjMzIDE5NjMuMzMsNDUwMy4zMyAxOTk2LjY3LDQ0NDYuNjcgMjA0MCw0NDAzLjMzIDIwODYuNjcsNDM2MCAyMTM2LjY3LDQzMjYuNjcgMjE5My4zMyw0MzAzLjMzIDIyNDMuMzMsNDI5MCAyMjg2LjY3LDQyODYuNjcgNjM0Ni42Nyw0Mjg2LjY3IDcwNDMuMzMsMzYyMCAyMjkwLDM2MTYuNjcgMjE4My4zMywzNjMzLjMzIDIwOTYuNjcsMzY1Ni42NyAyMDAzLjMzLDM2ODMuMzMgMTkwMCwzNzMwIDE4MTMuMzMsMzc3Ni42NyAxNzIzLjMzLDM4NDAgMTY2Ni42NywzODc2LjY3IDE1OTAsMzk0My4zMyAxNTIwLDQwMjMuMzMgMTQ3MCw0MDgzLjMzIDE0MTMuMzMsNDE2MCAxMzUzLjMzLDQyNzMuMzMgMTMxMy4zMyw0MzcwIDEyODAsNDQ4My4zMyAxMjYwLDQ1ODMuMzMgMTI1Ni42Nyw0NjkwIDEyNjMuMzMsNDc5My4zMyAxMjgzLjMzLDQ4OTYuNjcgMTMyMCw1MDEzLjMzIDEzNTAsNTA3MCAxMzkzLjMzLDUxNDMuMzMgMTQ1MCw1MjE2LjY3IDE0OTYuNjcsNTI2My4zMyAxNTU2LjY3LDUzMTAgMTY0MCw1MzYzLjMzIDE3MTYuNjcsNTQwNi42NyAxNzkwLDU0MzMuMzMgMTg1MCw1NDUwIDE5MTAsNTQ2Ni42NyAxOTYzLjMzLDU0NzMuMzMgMjAwMy4zMyw1NDc2LjY3IDIwODMuMzMsNTQ4MCAyNjQwLDU0ODAgIi8+DQogPHBvbHlnb24gY2xhc3M9ImZpbDAgc3RyMCIgcG9pbnRzPSIyMjEzLjMzLDQ3NzYuNjcgMjg3My4zMyw1NDc2LjY3IDMzNzMuMzMsNTQ3Ni42NyAzMzk2LjY3LDU0ODAgMzQyNi42Nyw1NDkzLjMzIDM0NTYuNjcsNTUxMCAzNDc2LjY3LDU1MjYuNjcgMzQ5Ni42Nyw1NTQzLjMzIDM1MTYuNjcsNTU3MCAzNTMzLjMzLDU2MDAgMzU0MCw1NjIwIDM1NDYuNjcsNTY0My4zMyAzNTQ2LjY3LDU2NzAgMzU0Ni42Nyw1NzAwIDM1NDAsNTczMCAzNTI2LjY3LDU3NzAgMzUxMy4zMyw1ODAwIDM0OTMuMzMsNTgzMCAzNDczLjMzLDU4NTMuMzMgMzQ0MCw1ODgzLjMzIDM0MDYuNjcsNTkwNi42NyAzMzYzLjMzLDU5MjYuNjcgMzMzMCw1OTQwIDE1MjYuNjcsNTk0My4zMyA4NDAsNjYxMy4zMyAzMjIwLDY2MTAgMzI2MCw2NjEwIDMzMTMuMzMsNjYwNi42NyAzMzY2LjY3LDY1OTYuNjcgMzQyMCw2NTgwIDM0OTMuMzMsNjU2MCAzNTUwLDY1MzYuNjcgMzYwNi42Nyw2NTEzLjMzIDM2NjYuNjcsNjQ3Ni42NyAzNzQzLjMzLDY0MzAgMzgxNi42Nyw2MzgzLjMzIDM4ODAsNjMyNi42NyAzOTMzLjMzLDYyNzYuNjcgMzk5MCw2MjEzLjMzIDQwNTYuNjcsNjEzMCA0MTE2LjY3LDYwMzAgNDE1My4zMyw1OTYwIDQxODYuNjcsNTg3My4zMyA0MjE2LjY3LDU3ODAgNDIyNi42Nyw1NzE2LjY3IDQyMzMuMzMsNTY2My4zMyA0MjMwLDU0OTYuNjcgNDIxMCw1MzkwIDQxNzYuNjcsNTI4Ni42NyA0MTQzLjMzLDUyMDYuNjcgNDA5MCw1MTE2LjY3IDQwNDAsNTA1Ni42NyAzOTgwLDQ5OTMuMzMgMzkyMCw0OTQzLjMzIDM4NDAsNDg5MCAzNzU2LjY3LDQ4NDYuNjcgMzY1My4zMyw0ODEwIDM1NzMuMzMsNDc5My4zMyAzNTIzLjMzLDQ3ODYuNjcgMzQ0My4zMyw0NzgzLjMzICIvPg0KPC9zeW1ib2w+DQogPGcgaWQ9IkxheWVyX3gwMDIwXzEiIHZpc2liaWxpdHk9ImhpZGRlbiI+DQogIDxtZXRhZGF0YSBpZD0iQ29yZWxDb3JwSURfMENvcmVsLUxheWVyIi8+DQogIDxpbWFnZSB4PSIyMCIgeT0iMjYuNjciIHdpZHRoPSI5OTUwIiBoZWlnaHQ9Ijk5NTAiIHhsaW5rOmhyZWY9IlNUS2Nkcl9JbWFnZXNcU1RLY2RyX0ltZ0lEMS5wbmciLz4NCiA8L2c+DQogPGcgaWQ9IkxheWVyX3gwMDIwXzIiPg0KICA8bWV0YWRhdGEgaWQ9IkNvcmVsQ29ycElEXzFDb3JlbC1MYXllciIvPg0KICA8dXNlIGlkPSJTeW1ib2wxXzAiIHg9IjgzNy42NyIgeT0iMzM4MCIgd2lkdGg9IjgzMjQuNjQiIGhlaWdodD0iMzI0MCIgeGxpbms6aHJlZj0iI1N5bWJvbDEiLz4NCiA8L2c+DQo8L3N2Zz4NCg==" alt="STX Enterprises Logo">
                    <div class="sub">DIGITAL EMPIRE STUDIO</div>
                </div>
                <div class="inv-tag">
                    <div class="label">INVOICE</div>
                    <div class="num">No. STX-{{ date('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            <!-- Teal / Gradient Bar -->
            <div class="inv-tealbar"></div>

            <!-- Meta Data Grid -->
            <div class="inv-meta">
                <div class="block">
                    <div class="h">Billed to</div>
                    <div class="l">
                        <strong>{{ $order->user->username ?? 'Valued Client' }}</strong>
                        Account ID: STX-ACC-{{ $order->user_id }}<br>
                        {{ $order->user->email ?? 'N/A' }}<br>
                        Sri Lanka
                    </div>
                </div>
                <div class="block">
                    <div class="h">Invoice details</div>
                    <div class="l">
                        Issue date: <strong>{{ $order->created_at->format('d M Y') }}</strong><br>
                        Order ref: <strong>{{ $order->order_number }}</strong><br>
                        Currency: <strong>LKR (Sri Lankan Rupee)</strong>
                    </div>
                </div>
                <div class="block">
                    <div class="h">Pay to</div>
                    <div class="l">
                        <strong>STX Enterprises</strong> (Shanudha Tirosh)<br>
                        Channel: <strong>{{ $order->paymentMethod->name ?? 'Commercial Bank of Ceylon' }}</strong><br>
                        Negombo, Western Province, Sri Lanka<br>
                        hello@stxenterprises.com
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <table class="items">
                <thead>
                    <tr>
                        <th style="width:52%;">Description</th>
                        <th class="qty" style="width:12%;">Qty</th>
                        <th class="cycle" style="width:18%;">Cycle</th>
                        <th style="width:18%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="desc">
                            <strong>{{ $order->plan_snapshot['plan_name'] ?? ($order->plan->name ?? 'Hosting Service') }}</strong>
                            <span>
                                {{ $order->plan_snapshot['product_name'] ?? 'Cloud Node' }} &bull;
                                RAM: {{ number_format(($order->plan_snapshot['memory_mb'] ?? 2048) / 1024, 1) }} GB &bull;
                                CPU: {{ $order->plan_snapshot['cpu_percent'] ?? 100 }}% &bull;
                                NVMe: {{ number_format(($order->plan_snapshot['disk_mb'] ?? 20480) / 1024, 0) }} GB
                                (Instance: {{ $order->server_configuration['requested_name'] ?? 'Production' }})
                            </span>
                        </td>
                        <td class="qty">1</td>
                        <td class="cycle" style="text-transform:capitalize;">
                            {{ $order->plan_snapshot['billing_period'] ?? 'Monthly' }}
                        </td>
                        <td>LKR {{ number_format((float) $order->subtotal_lkr, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="inv-totals">
                <div class="box">
                    <div class="row">
                        <span>Subtotal</span>
                        <span>LKR {{ number_format((float) $order->subtotal_lkr, 2) }}</span>
                    </div>
                    @if($order->discount_lkr > 0)
                    <div class="row">
                        <span>Discount</span>
                        <span>- LKR {{ number_format((float) $order->discount_lkr, 2) }}</span>
                    </div>
                    @endif
                    <div class="row">
                        <span>Tax (0%)</span>
                        <span>LKR 0.00</span>
                    </div>
                    <div class="row total">
                        <span>Total Due</span>
                        <span class="amt">LKR {{ number_format((float) $order->total_amount_lkr, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Notes & Status Stamp -->
            <div class="inv-bottom">
                <div>
                    <div class="h">Notes</div>
                    <div class="l">
                        Thank you for choosing STX Enterprises. Payment verification is handled promptly upon slip submission. For billing assistance or server upgrades, please contact hello@stxenterprises.com or open a ticket in your client dashboard.
                    </div>
                </div>
                <div>
                    <div class="h">Status</div>
                    @if($order->status->value === 'completed' || $order->status->value === 'approved')
                        <div class="inv-status paid">Paid &bull; Verified</div>
                    @elseif($order->status->value === 'under_review')
                        <div class="inv-status review">Payment Under Review</div>
                    @else
                        <div class="inv-status awaiting">Awaiting Payment</div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</body>
</html>
