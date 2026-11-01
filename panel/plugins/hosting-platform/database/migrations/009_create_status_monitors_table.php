<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_monitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('http'); // http, tcp, ping, database, redis, node
            $table->string('target_url')->nullable(); // URL, host:port, or domain
            $table->string('category')->default('Platform Services'); // Core Services, Infrastructure, Game Nodes, Payment Rails
            $table->integer('expected_status_code')->default(200);
            $table->integer('check_interval_seconds')->default(60);
            $table->integer('timeout_seconds')->default(5);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('current_status')->default('operational'); // operational, degraded, down, maintenance
            $table->decimal('current_latency_ms', 8, 2)->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('last_status_change_at')->nullable();
            $table->decimal('uptime_percentage', 5, 2)->default(100.00);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_monitors');
    }
};
