<?php

namespace BrandedHosting\Platform\Services;

use BrandedHosting\Platform\Exceptions\ProofRejectedException;
use BrandedHosting\Platform\Models\HostingPaymentProof;
use BrandedHosting\Platform\Models\HostingPaymentSubmission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PaymentProofService
{
    /** Real MIME type => extension we store. The client-supplied extension is never trusted. */
    protected const MIME_EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'application/pdf' => 'pdf',
    ];

    /**
     * Validate and store an uploaded payment proof.
     *
     * @throws ProofRejectedException when the file is refused for a customer-safe reason
     */
    public function storeProof(HostingPaymentSubmission $submission, UploadedFile $file): HostingPaymentProof
    {
        $maxBytes = (int) config('hosting-platform.proof_uploads.max_file_size_kb', 10240) * 1024;
        if ($file->getSize() === false || $file->getSize() > $maxBytes) {
            throw new ProofRejectedException('That file is too large. The maximum size is ' . round($maxBytes / 1048576) . ' MB.');
        }

        $mime = $file->getMimeType();
        $extension = self::MIME_EXTENSIONS[$mime] ?? null;
        if ($extension === null) {
            throw new ProofRejectedException('Only JPG, PNG and WEBP images or PDF documents are accepted.');
        }

        $hash = hash_file('sha256', $file->getRealPath());

        // The same slip must not be reused to pay for a second order, or the same order twice.
        $duplicate = HostingPaymentProof::query()->where('file_sha256', $hash)->exists();
        if ($duplicate) {
            throw new ProofRejectedException('This payment slip has already been submitted. Please upload the slip for this payment.');
        }

        $disk = (string) config('hosting-platform.proof_uploads.disk', 'local');
        $directory = 'proofs/' . now()->format('Y/m');
        $path = $file->storeAs($directory, Str::random(40) . '.' . $extension, $disk);

        if (!$path) {
            throw new ProofRejectedException('We could not store your file. Please try again.');
        }

        try {
            return HostingPaymentProof::create([
                'submission_id' => $submission->id,
                'file_path' => $path,
                'storage_disk' => $disk,
                'original_filename' => Str::limit(preg_replace('/[^\p{L}\p{N} _.\-]/u', '_', $file->getClientOriginalName()), 150, ''),
                'mime_type' => $mime,
                'file_size_bytes' => $file->getSize(),
                'file_sha256' => $hash,
            ]);
        } catch (Throwable $e) {
            // Do not leave an orphaned file behind when the database write fails.
            Storage::disk($disk)->delete($path);

            throw $e;
        }
    }
}
