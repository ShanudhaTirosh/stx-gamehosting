<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusIncident extends Model
{
    protected $table = 'status_incidents';

    protected $fillable = [
        'title',
        'type', // incident, maintenance
        'status', // investigating, identified, monitoring, resolved, scheduled, in_progress, completed
        'impact', // none, minor, major, critical
        'affected_monitors',
        'scheduled_start',
        'scheduled_end',
        'resolved_at',
    ];

    protected $casts = [
        'affected_monitors' => 'array',
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function updates(): HasMany
    {
        return $this->hasMany(StatusIncidentUpdate::class, 'status_incident_id')->orderBy('created_at', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['resolved', 'completed']);
    }
}
