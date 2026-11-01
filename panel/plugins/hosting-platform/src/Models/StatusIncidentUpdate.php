<?php

namespace BrandedHosting\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusIncidentUpdate extends Model
{
    protected $table = 'status_incident_updates';

    protected $fillable = [
        'status_incident_id',
        'status',
        'message',
    ];

    public function incident(): BelongsTo
    {
        return $this->belongsTo(StatusIncident::class, 'status_incident_id');
    }
}
