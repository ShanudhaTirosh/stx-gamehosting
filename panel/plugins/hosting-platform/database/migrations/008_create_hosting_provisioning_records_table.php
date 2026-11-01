<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_provisioning_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('hosting_orders')->cascadeOnDelete();
            $table->unsignedInteger('server_id')->nullable();
            $table->unsignedInteger('node_id')->nullable();
            $table->unsignedInteger('allocation_id')->nullable();

            $table->string('status')->default('pending'); // ProvisioningStatus enum
            $table->integer('attempts')->default(0);
            $table->text('last_error')->nullable();
            $table->json('execution_log')->nullable();
            
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers')->nullOnDelete();
            $table->foreign('node_id')->references('id')->on('nodes')->nullOnDelete();
            $table->foreign('allocation_id')->references('id')->on('allocations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_provisioning_records');
    }
};
