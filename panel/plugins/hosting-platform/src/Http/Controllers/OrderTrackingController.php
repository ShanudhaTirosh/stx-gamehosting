<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use BrandedHosting\Platform\Enums\OrderStatus;
use BrandedHosting\Platform\Enums\VerificationStatus;
use BrandedHosting\Platform\Models\HostingOrder;
use BrandedHosting\Platform\Models\HostingPaymentMethod;
use BrandedHosting\Platform\Models\HostingPaymentSubmission;
use BrandedHosting\Platform\Services\PaymentProofService;
use BrandedHosting\Platform\Exceptions\ProofRejectedException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class OrderTrackingController extends Controller
{
    protected const MAX_SUBMISSIONS = 5;

    /**
     * Determine if current user or guest session has authorized access to this order.
     */
    protected function authorizeOrderAccess(HostingOrder $order): void
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            if ($user->isRootAdmin() || $order->user_id === $user->id) {
                return;
            }
        } elseif (session()->has("order_access_{$order->uuid}")) {
            return;
        }

        abort(403, 'Unauthorized access to order details.');
    }

    /**
     * Customer view for order status, payment instructions, and proof upload.
     */
    public function show(string $uuid): View
    {
        $order = HostingOrder::query()
            ->where('uuid', $uuid)
            ->with(['plan.product.category', 'paymentMethod', 'paymentSubmissions.proofs', 'server'])
            ->firstOrFail();

        $this->authorizeOrderAccess($order);

        $allPaymentMethods = HostingPaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('hosting-platform::storefront.order_tracking', [
            'order' => $order,
            'paymentMethods' => $allPaymentMethods,
        ]);
    }

    /**
     * Customer view for official downloadable and printable order invoice.
     */
    public function invoice(string $uuid): View
    {
        $order = HostingOrder::query()
            ->where('uuid', $uuid)
            ->with(['plan.product', 'user', 'paymentMethod', 'server'])
            ->firstOrFail();

        $this->authorizeOrderAccess($order);

        return view('hosting-platform::storefront.invoice', [
            'order' => $order,
        ]);
    }

    /**
     * Handle payment proof and transaction reference submission from customer.
     */
    public function submitProof(Request $request, string $uuid, PaymentProofService $proofService): RedirectResponse
    {
        $order = HostingOrder::query()->where('uuid', $uuid)->firstOrFail();

        $this->authorizeOrderAccess($order);

        // A paid, provisioned or cancelled order must never be pushed back into review.
        if (!in_array($order->status, [OrderStatus::PendingPayment, OrderStatus::UnderReview, OrderStatus::Rejected], true)) {
            return back()->withErrors(['proof_file' => 'This order is no longer accepting payment proofs.']);
        }

        if ($order->paymentSubmissions()->count() >= self::MAX_SUBMISSIONS) {
            return back()->withErrors(['proof_file' => 'Too many submissions for this order. Please contact support.']);
        }

        $validated = $request->validate([
            'payment_method_id' => ['required', 'integer', Rule::exists('hosting_payment_methods', 'id')->where('is_active', true)],
            'transaction_reference' => ['required', 'string', 'max:100'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
            'proof_file' => ['required', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:' . (int) config('hosting-platform.proof_uploads.max_file_size_kb', 10240)],
        ]);

        try {
            DB::transaction(function () use ($order, $validated, $request, $proofService) {
                $submission = HostingPaymentSubmission::create([
                    'order_id' => $order->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    // What the customer claims to have paid; staff verify the real amount on the slip.
                    'amount_paid_lkr' => $order->total_amount_lkr,
                    'transaction_reference' => $validated['transaction_reference'],
                    'customer_notes' => $validated['customer_notes'] ?? null,
                    'verification_status' => VerificationStatus::Pending,
                ]);

                $proofService->storeProof($submission, $request->file('proof_file'));

                $order->update([
                    'payment_method_id' => $validated['payment_method_id'],
                    'status' => OrderStatus::UnderReview,
                ]);
            });
        } catch (ProofRejectedException $e) {
            return back()->withErrors(['proof_file' => $e->getMessage()]);
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['proof_file' => 'We could not process your payment proof. Please try again.']);
        }

        return back()->with('success', 'Payment proof submitted. Our billing team will verify it shortly.');
    }
}
