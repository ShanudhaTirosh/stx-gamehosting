# Staging checklist

Run this on a staging copy (not production) before taking real orders. None of the hardening, redesign or
billing changes were run against a live database when they were written, so this is the first real test.
Each step says what you should see. Anything else is a bug: note the step and the error in `storage/logs`.

## 0. Setup
- [ ] `docker compose up -d --build` starts clean; `docker compose ps` shows panel, database and cache healthy.
- [ ] `docker compose exec panel php artisan migrate:status` shows `016_add_billing_lifecycle_to_hosting_orders` as Ran.
- [ ] `docker compose exec panel php artisan p:user:make` creates your admin; you can log in at `/admin`.
- [ ] A Wings node is installed, shows green under Admin > Nodes, is tagged `game-node` (+ `sg` / `de`), and has free port allocations.
- [ ] At least one product has an egg, and every required egg variable has a default.
- [ ] Mail works: Admin > Settings > Mail > send a test email.

## 1. Storefront renders
- [ ] `/`, `/hosting/games`, a category page, `/about`, `/contact`, `/status`, `/help`: all load, no PHP errors, fonts and colours look right on desktop and phone width.
- [ ] Footer shows your real email/phone (set under Admin > SEO Settings). No placeholder numbers.
- [ ] Homepage "from" prices match your real cheapest plan per game.

## 2. Ordering
- [ ] Checkout for a plan with a setup fee: summary total = price + setup fee, and the order page shows the same total.
- [ ] Guest checkout creates an account and lands on the order page. Repeating with the same email gives a generic error, not "email taken".
- [ ] Place 6 orders without paying: the 6th is refused (open-order limit).
- [ ] Upload a valid slip: status becomes Under review. Upload the **same file** again: refused as already submitted.
- [ ] Upload a `.php` renamed to `.jpg`: refused. Upload a PDF: it downloads rather than opening in the browser.
- [ ] Submit the contact form: an email arrives at your contact address; the page does not show a fake ticket number.
- [ ] Hit `/checkout/<plan>` POST 11 times quickly: you get rate limited (HTTP 429).

## 3. Provisioning
- [ ] Admin > Orders > Approve & Deploy on a pending order. The order moves to Provisioning, then Completed within a minute or two, and a real server appears on the node.
- [ ] Double-click Approve: only one server is created.
- [ ] Stop Wings, approve another order: the order returns to Approved with an error on its provisioning record, no server row is left behind. Start Wings and press Retry Deploy: it completes.
- [ ] With no node tagged `game-node`: approving gives "no viable node" and the order stays Approved. Nothing invented.
- [ ] The customer's order page shows "Your server is live", the address and a control panel link.

## 4. Billing lifecycle
Replace `ORD-XXXX` with a completed order. Use `php artisan tinker` inside the container.

- [ ] `docker compose exec panel php artisan hosting:run-billing --dry-run` runs and reports counts without changing anything.
- [ ] Set `paid_until` to 5 days from now (`HostingOrder::where('order_number','ORD-XXXX')->update(['paid_until'=>now()->addDays(5)])`), run `hosting:run-billing`: a renewal order is created, an invoice email arrives, the order page shows a Renew button.
- [ ] Set `paid_until` to 2 days away and run again: a reminder email arrives (once; a second run sends nothing).
- [ ] Customer uploads a slip on the **renewal** order, you approve it in admin: the original order's `paid_until` moves forward by one period and a confirmation email arrives.
- [ ] Set `paid_until` to 3 days **ago** with an invoice older than 24 h and no slip: run billing. The server is suspended on the node, the customer gets a suspension email, the order page says suspended.
- [ ] While suspended, approve the renewal: the server starts again and the suspended flag clears.
- [ ] Admin > Orders: the Paid until column colours correctly, and the Suspended / Due within 7 days filters work. Extend adds days, Unsuspend works.
- [ ] A renewal with a slip still Under review does **not** get suspended.

## 5. Status page
- [ ] Monitors show real status. A brand new monitor shows grey history, not green.
- [ ] `/api/status/ping` returns JSON quickly and does **not** trigger probes (check the monitor's last-checked time does not change when you call it).
- [ ] View source of `/status` and `/status/data`: no internal hostnames or URLs anywhere.

## 6. Before you go live
- [ ] Backups of the `stx-db` and `stx-panel-data` volumes and your `.env` are scheduled and a restore has been tried once.
- [ ] HTTPS is on and `APP_URL` is the https address.
- [ ] Terms of Service reviewed against what you can actually deliver (see the notes in the handover).
