<?php

namespace BrandedHosting\Platform\Services;

use BrandedHosting\Platform\Models\HostingPaymentProof;
use BrandedHosting\Platform\Models\HostingPaymentSubmission;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentProofService
{
    protected const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
    ];

    protected const MAX_FILE_SIZE_BYTES = 10 * 1024 * 1024; // 10MB

    /**
     * Store and validate an uploaded payment proof.
     *
     * @throws Exception
     */
    public function storeProof(HostingPaymentSubmission $submission, UploadedFile $file): HostingPaymentProof
    {
        if ($file->getSize() > self::MAX_FILE_SIZE_BYTES) {
            throw new Exception('Uploaded proof exceeds maximum permitted file size of 10MB.');
        }

        $realMime = $file->getMimeType();
        if (!in_array($realMime, self::ALLOWED_MIME_TYPES, true)) {
            throw new Exception("Invalid file type ({$realMime}). Only JPG, PNG, WEBP images and PDF documents are accepted.");
        }

        $hash = hash_file('sha256', $file->getRealPath());

        $yearMonth = now()->format('Y/m');
        $randomName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $targetDirectory = "proofs/{$yearMonth}";

        // Store to private storage disk
        $path = $file->storeAs($targetDirectory, $randomName, 'local');

        if (!$path) {
            throw new Exception('Failed to securely store payment proof file.');
        }

        return HostingPaymentProof::create([
            'submission_id' => $submission->id,
            'file_path' => $path,
            'storage_disk' => 'local',
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $realMime,
            'file_size_bytes' => $file->getSize(),
            'file_sha256' => $hash,
        ]);
    }
}
