<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HostingPaymentProof extends Model
{
    protected $table = 'hosting_payment_proofs';

    protected $fillable = [
        'submission_id',
        'file_path',
        'storage_disk',
        'original_filename',
        'mime_type',
        'file_size_bytes',
        'file_sha256',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(HostingPaymentSubmission::class, 'submission_id');
    }

    public function getDownloadUrl(): string
    {
        return route('hosting.proof.download', ['proof' => $this->id]);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }
}
