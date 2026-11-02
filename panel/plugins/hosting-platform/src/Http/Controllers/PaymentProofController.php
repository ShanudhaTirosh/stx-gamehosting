<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use BrandedHosting\Platform\Models\HostingPaymentProof;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;

class PaymentProofController extends Controller
{
    /**
     * Stream a payment proof to staff or to the customer who owns the order.
     */
    public function download(int $proofId): BinaryFileResponse
    {
        $proof = HostingPaymentProof::query()
            ->with('submission.order')
            ->findOrFail($proofId);

        $user = Auth::user();
        abort_unless($user, 403, 'Unauthorized access to payment proof.');

        $isStaff = $user->isRootAdmin() || $user->can('viewPaymentProof hostingOrder');
        $isOwner = $user->id === $proof->submission?->order?->user_id;
        abort_unless($isStaff || $isOwner, 403, 'Unauthorized access to payment proof.');

        $disk = Storage::disk($proof->storage_disk);
        abort_unless($disk->exists($proof->file_path), 404);

        // Images are shown inline; PDFs are always downloaded so no script in a PDF can run on our origin.
        $disposition = HeaderUtils::makeDisposition(
            $proof->mime_type === 'application/pdf' ? HeaderUtils::DISPOSITION_ATTACHMENT : HeaderUtils::DISPOSITION_INLINE,
            $proof->original_filename ?: 'payment-proof',
            'payment-proof'
        );

        return response()->file($disk->path($proof->file_path), [
            'Content-Type' => $proof->mime_type,
            'Content-Disposition' => $disposition,
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; sandbox",
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
