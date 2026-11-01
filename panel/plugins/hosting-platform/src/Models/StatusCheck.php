<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusCheck extends Model
{
    public $timestamps = false;
    protected $table = 'status_checks';

    protected $fillable = [
        'status_monitor_id',
        'is_ok',
        'latency_ms',
        'status_code',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'is_ok' => 'boolean',
        'latency_ms' => 'float',
        'status_code' => 'integer',
        'created_at' => 'datetime',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(StatusMonitor::class, 'status_monitor_id');
    }
}
