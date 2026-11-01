<?php

namespace BrandedHosting\Platform\Models;

use App\Models\BackupHost;
use App\Models\DatabaseHost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostingCategoryConfig extends Model
{
    protected $table = 'hosting_category_configs';

    protected $fillable = [
        'category_id',
        // Subdomains
        'subdomains_enabled',
        'subdomain_limit',
        'default_domain',
        'subdomain_prefix_pattern',
        'allow_custom_subdomain',
        // Databases
        'databases_enabled',
        'database_limit',
        'auto_create_database',
        'database_host_id',
        'default_database_prefix',
        // Backups
        'backups_enabled',
        'backup_limit',
        'auto_backup_enabled',
        'auto_backup_cron',
        'auto_backup_timezone',
        'backup_host_id',
        // Network Allocations
        'allocation_limit',
        'auto_assign_ports',
        'default_port',
        'port_range_start',
        'port_range_end',
    ];

    protected $casts = [
        'subdomains_enabled' => 'boolean',
        'subdomain_limit' => 'integer',
        'allow_custom_subdomain' => 'boolean',
        'databases_enabled' => 'boolean',
        'database_limit' => 'integer',
        'auto_create_database' => 'boolean',
        'database_host_id' => 'integer',
        'backups_enabled' => 'boolean',
        'backup_limit' => 'integer',
        'auto_backup_enabled' => 'boolean',
        'backup_host_id' => 'integer',
        'allocation_limit' => 'integer',
        'auto_assign_ports' => 'boolean',
        'default_port' => 'integer',
        'port_range_start' => 'integer',
        'port_range_end' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(HostingCategory::class, 'category_id');
    }

    public function databaseHost(): BelongsTo
    {
        return $this->belongsTo(DatabaseHost::class, 'database_host_id');
    }

    public function backupHost(): BelongsTo
    {
        return $this->belongsTo(BackupHost::class, 'backup_host_id');
    }
}
