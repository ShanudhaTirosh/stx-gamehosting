<?php

namespace BrandedHosting\Platform\Models;

use App\Models\Allocation;
use App\Models\Node;
use App\Models\Server;
use BrandedHosting\Platform\Enums\ProvisioningStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostingProvisioningRecord extends Model
{
    protected $table = 'hosting_provisioning_records';

    protected $fillable = [
        'order_id',
        'server_id',
        'node_id',
        'allocation_id',
        'status',
        'attempts',
        'last_error',
        'execution_log',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => ProvisioningStatus::class,
        'attempts' => 'integer',
        'execution_log' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(HostingOrder::class, 'order_id');
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class, 'server_id');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class, 'allocation_id');
    }
}
