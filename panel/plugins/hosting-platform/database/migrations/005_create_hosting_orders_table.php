<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_number')->unique();
            
            $table->unsignedInteger('user_id');
            $table->foreignId('plan_id')->constrained('hosting_plans')->restrictOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('hosting_payment_methods')->nullOnDelete();
            $table->unsignedInteger('server_id')->nullable();

            $table->string('status')->default('pending_payment'); // OrderStatus enum
            
            // Financials in LKR
            $table->decimal('subtotal_lkr', 10, 2);
            $table->decimal('discount_lkr', 10, 2)->default(0.00);
            $table->decimal('total_amount_lkr', 10, 2);

            // Immutable Order-Time Snapshot of plan and specifications
            $table->json('plan_snapshot');
            $table->json('server_configuration')->nullable();

            $table->dateTime('expires_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('provisioned_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('server_id')->references('id')->on('servers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_orders');
    }
};
