<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\HostingPaymentMethod;
use BrandedHosting\Platform\Models\HostingPlan;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page for a specific plan.
     */
    public function show(string $slug): View
    {
        $plan = HostingPlan::query()
            ->where('slug', $slug)
            ->where('is_available', true)
            ->with('product.category')
            ->firstOrFail();

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
     * Process checkout form, register guest if necessary, create order with immutable snapshot.
     */
    public function process(Request $request, string $slug): RedirectResponse
    {
        $plan = HostingPlan::query()
            ->where('slug', $slug)
            ->where('is_available', true)
            ->with('product')
            ->firstOrFail();

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:hosting_payment_methods,id',
            'server_name' => 'nullable|string|max:100',
            'server_location' => 'nullable|string|max:50',
            // Guest registration fields if not logged in
            'guest_name' => 'nullable|string|max:100',
            'guest_email' => 'nullable|email|max:191',
            'guest_password' => 'nullable|string|min:8',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            // If guest, create or find user account
            if (!$user) {
                if (empty($validated['guest_email']) || empty($validated['guest_password'])) {
                    return back()->withErrors(['guest_email' => 'Please sign in or provide email and password to create an account.'])->withInput();
                }

                $existingUser = User::where('email', $validated['guest_email'])->first();
                if ($existingUser) {
                    return back()->withErrors(['guest_email' => 'An account with this email already exists. Please login first.'])->withInput();
                }

                $username = Str::slug($validated['guest_name'] ?? explode('@', $validated['guest_email'])[0]) . '_' . Str::random(4);

                $user = User::create([
                    'username' => $username,
                    'email' => $validated['guest_email'],
                    'password' => Hash::make($validated['guest_password']),
                ]);

                Auth::login($user);
            }

            // Create immutable snapshot of plan and hardware specifications
            $snapshot = [
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

            $totalAmount = (float) $plan->price_lkr + (float) $plan->setup_fee_lkr;

            $order = HostingOrder::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'payment_method_id' => $validated['payment_method_id'],
                'status' => OrderStatus::PendingPayment,
                'subtotal_lkr' => $totalAmount,
                'discount_lkr' => 0.00,
                'total_amount_lkr' => $totalAmount,
                'plan_snapshot' => $snapshot,
                'server_configuration' => [
                    'requested_name' => $validated['server_name'] ?? ($plan->name . ' Server'),
                    'location' => $validated['server_location'] ?? 'default',
                ],
                'expires_at' => now()->addDays(3), // 3 days to submit proof before cancellation
            ]);

            DB::commit();

            session()->put("order_access_{$order->uuid}", true);

            return redirect()->route('hosting.order.tracking', ['uuid' => $order->uuid])
                ->with('success', 'Order created successfully! Please complete payment using the instructions below.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Checkout failed: ' . $e->getMessage()])->withInput();
        }
    }
}
