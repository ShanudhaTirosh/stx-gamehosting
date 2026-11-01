<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_category_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('hosting_categories')->cascadeOnDelete()->unique();

            // Subdomains Configuration
            $table->boolean('subdomains_enabled')->default(true);
            $table->unsignedInteger('subdomain_limit')->default(1);
            $table->string('default_domain')->default('shanutechx.com');
            $table->string('subdomain_prefix_pattern')->default('{order_ref}.play');
            $table->boolean('allow_custom_subdomain')->default(true);

            // Database (DB) Configuration
            $table->boolean('databases_enabled')->default(true);
            $table->unsignedInteger('database_limit')->default(2);
            $table->boolean('auto_create_database')->default(true);
            $table->unsignedInteger('database_host_id')->nullable();
            $table->string('default_database_prefix')->default('primary');

            // Backups & Cloud Vault Configuration
            $table->boolean('backups_enabled')->default(true);
            $table->unsignedInteger('backup_limit')->default(3);
            $table->boolean('auto_backup_enabled')->default(true);
            $table->string('auto_backup_cron')->default('0 4 * * *');
            $table->string('auto_backup_timezone')->default('UTC');
            $table->unsignedInteger('backup_host_id')->nullable();

            // Network Allocations & Port Configuration
            $table->unsignedInteger('allocation_limit')->default(2);
            $table->boolean('auto_assign_ports')->default(true);
            $table->unsignedInteger('default_port')->nullable();
            $table->unsignedInteger('port_range_start')->nullable()->default(25565);
            $table->unsignedInteger('port_range_end')->nullable()->default(25590);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_category_configs');
    }
};
