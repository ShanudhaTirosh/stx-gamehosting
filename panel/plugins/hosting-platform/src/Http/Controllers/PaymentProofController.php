<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use BrandedHosting\Platform\Models\HostingPaymentProof;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PaymentProofController extends Controller
{
    /**
     * Stream payment proof safely to authorized staff or the owning customer.
     */
    public function download(int $proofId): BinaryFileResponse|Response
    {
        $proof = HostingPaymentProof::query()
            ->with('submission.order')
            ->findOrFail($proofId);

        $user = Auth::user();

        // Must be staff or owning customer
        $orderUserId = $proof->submission?->order?->user_id;
        $isStaff = ($user && method_exists($user, 'isRootAdmin') && $user->isRootAdmin()) || $user?->can('view paymentProof');
        $isOwner = $user && $user->id === $orderUserId;

        if (!$isStaff && !$isOwner) {
            abort(403, 'Unauthorized access to payment proof.');
        }

        $disk = Storage::disk($proof->storage_disk);
        if (!$disk->exists($proof->file_path)) {
            abort(404, 'Payment proof file not found on storage disk.');
        }

        $fullPath = $disk->path($proof->file_path);

        return response()->file($fullPath, [
            'Content-Type' => $proof->mime_type,
            'Content-Disposition' => 'inline; filename="' . $proof->original_filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
