<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class StatusMonitor extends Model
{
    protected $table = 'status_monitors';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'target_url',
        'category',
        'expected_status_code',
        'check_interval_seconds',
        'timeout_seconds',
        'is_active',
        'sort_order',
        'current_status',
        'current_latency_ms',
        'last_checked_at',
        'last_status_change_at',
        'uptime_percentage',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expected_status_code' => 'integer',
        'check_interval_seconds' => 'integer',
        'timeout_seconds' => 'integer',
        'sort_order' => 'integer',
        'current_latency_ms' => 'float',
        'uptime_percentage' => 'float',
        'last_checked_at' => 'datetime',
        'last_status_change_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($monitor) {
            if (empty($monitor->slug)) {
                $monitor->slug = Str::slug($monitor->name);
            }
        });
    }

    public function checks(): HasMany
    {
        return $this->hasMany(StatusCheck::class, 'status_monitor_id');
    }

    public function latestCheck(): HasOne
    {
        return $this->hasOne(StatusCheck::class, 'status_monitor_id')->latestOfMany('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Compute uptime percentage over a number of days (or hours/minutes).
     */
    public function recalculateUptime(float $days = 30): float
    {
        $since = now()->subMinutes(round($days * 1440));
        $total = $this->checks()->where('created_at', '>=', $since)->count();
        if ($total === 0) {
            return 100.00;
        }
        $okCount = $this->checks()->where('created_at', '>=', $since)->where('is_ok', true)->count();
        $pct = round(($okCount / $total) * 100, 2);
        $this->update(['uptime_percentage' => $pct]);
        return $pct;
    }
}
