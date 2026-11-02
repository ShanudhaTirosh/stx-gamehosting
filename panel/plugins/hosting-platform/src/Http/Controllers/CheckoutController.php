<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\HostingPaymentMethod;
use BrandedHosting\Platform\Models\HostingPlan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class CheckoutController extends Controller
{
    /** Unpaid orders one account may hold at the same time. */
    protected const MAX_OPEN_ORDERS = 5;

    public function show(string $slug): View
    {
        $plan = $this->availablePlan($slug);

        $paymentMethods = HostingPaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('hosting-platform::storefront.checkout', [
            'plan' => $plan,
            'paymentMethods' => $paymentMethods,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Create the order with an immutable snapshot of the plan, registering the customer if needed.
     */
    public function process(Request $request, string $slug): RedirectResponse
    {
        $plan = $this->availablePlan($slug, withProduct: true);

        $regions = array_merge(['default'], array_keys((array) config('hosting-platform.provisioning.regions', [])));
        $isGuest = !Auth::check();

        if ($isGuest && !config('hosting-platform.storefront.allow_guest_checkout', true)) {
            throw ValidationException::withMessages(['guest_email' => 'Please sign in to place an order.']);
        }

        $validated = $request->validate([
            'payment_method_id' => ['required', 'integer', Rule::exists('hosting_payment_methods', 'id')->where('is_active', true)],
            'server_name' => ['nullable', 'string', 'max:60', 'regex:/^[^<>\x00-\x1F]+$/u'],
            'server_location' => ['nullable', 'string', Rule::in($regions)],
            'guest_name' => [Rule::requiredIf($isGuest), 'nullable', 'string', 'max:100'],
            'guest_email' => [Rule::requiredIf($isGuest), 'nullable', 'email:rfc', 'max:191'],
            'guest_password' => [Rule::requiredIf($isGuest), 'nullable', 'string', 'min:10', 'max:128'],
        ]);

        try {
            $order = DB::transaction(function () use ($validated, $plan, $isGuest) {
                $user = $isGuest ? $this->registerCustomer($validated) : Auth::user();

                $openOrders = HostingOrder::query()
                    ->where('user_id', $user->id)
                    ->whereIn('status', [OrderStatus::PendingPayment, OrderStatus::UnderReview])
                    ->count();

                if ($openOrders >= self::MAX_OPEN_ORDERS) {
                    throw ValidationException::withMessages([
                        'error' => 'You already have several unpaid orders. Complete or cancel them before placing a new one.',
                    ]);
                }

                $total = (float) $plan->price_lkr + (float) $plan->setup_fee_lkr;

                $order = HostingOrder::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'status' => OrderStatus::PendingPayment,
                    'subtotal_lkr' => $total,
                    'discount_lkr' => 0.00,
                    'total_amount_lkr' => $total,
                    'plan_snapshot' => $this->snapshot($plan),
                    'server_configuration' => [
                        'requested_name' => $validated['server_name'] ?? ($plan->name . ' Server'),
                        'location' => $validated['server_location'] ?? 'default',
                    ],
                    'expires_at' => now()->addDays((int) config('hosting-platform.orders.expiration_days', 3)),
                ]);

                $order->setRelation('user', $user);

                return $order;
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors(['error' => 'We could not place your order. Please try again, or contact support if it keeps happening.'])
                ->withInput($request->except('guest_password'));
        }

        // Log the new customer in only after the order is committed, and rotate the session ID.
        if ($isGuest) {
            Auth::login($order->user);
        }
        $request->session()->regenerate();
        $request->session()->put("order_access_{$order->uuid}", true);

        return redirect()
            ->route('hosting.order.tracking', ['uuid' => $order->uuid])
            ->with('success', 'Order created. Please complete payment using the instructions below.');
    }

    protected function availablePlan(string $slug, bool $withProduct = false): HostingPlan
    {
        return HostingPlan::query()
            ->where('slug', $slug)
            ->where('is_available', true)
            ->with($withProduct ? 'product' : 'product.category')
            ->firstOrFail();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function registerCustomer(array $data): User
    {
        $email = Str::lower(trim($data['guest_email']));

        // Same answer whether or not the address exists, so this form cannot be used to
        // discover which emails have accounts.
        if (User::query()->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'guest_email' => 'We could not create an account with those details. If you already have an account, please sign in first.',
            ]);
        }

        $base = preg_replace('/[^A-Za-z0-9_.-]/', '', Str::slug($data['guest_name'] ?? '', '_')) ?: 'customer';
        $base = Str::limit($base, 20, '');

        do {
            $username = $base . '_' . Str::lower(Str::random(5));
        } while (User::query()->where('username', $username)->exists());

        return User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($data['guest_password']),
        ]);
    }

    /**
     * Freeze everything that affects what the customer is buying.
     *
     * @return array<string, mixed>
     */
    protected function snapshot(HostingPlan $plan): array
    {
        return [
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'product_id' => $plan->product_id,
            'product_name' => $plan->product->name,
            'egg_id' => $plan->product->egg_id,
            'docker_image' => $plan->product->docker_image,
            'startup_command' => $plan->product->startup_command,
            'deployment_tags' => $plan->product->deployment_tags,
            'ports' => $plan->product->ports,
            'memory_mb' => $plan->memory_mb,
            'cpu_percent' => $plan->cpu_percent,
            'disk_mb' => $plan->disk_mb,
            'swap_mb' => $plan->swap_mb,
            'io_weight' => $plan->io_weight,
            'database_limit' => $plan->database_limit,
            'allocation_limit' => $plan->allocation_limit,
            'backup_limit' => $plan->backup_limit,
            'price_lkr' => (float) $plan->price_lkr,
            'setup_fee_lkr' => (float) $plan->setup_fee_lkr,
            'billing_period' => $plan->billing_period->value,
        ];
    }
}
