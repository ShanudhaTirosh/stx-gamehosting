<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_monitor_id')->constrained('status_monitors')->cascadeOnDelete();
            $table->boolean('is_ok')->default(true);
            $table->decimal('latency_ms', 8, 2)->nullable();
            $table->integer('status_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['status_monitor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_checks');
    }
};
