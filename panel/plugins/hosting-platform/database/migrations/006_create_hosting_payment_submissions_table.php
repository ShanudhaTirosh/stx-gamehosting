<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('hosting_orders')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained('hosting_payment_methods')->restrictOnDelete();
            
            $table->decimal('amount_paid_lkr', 10, 2);
            $table->string('transaction_reference')->nullable(); // Bank slip ref or SMS Txn ID
            $table->text('customer_notes')->nullable();

            $table->string('verification_status')->default('pending'); // VerificationStatus enum
            $table->unsignedInteger('verified_by_user_id')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->text('staff_decision_notes')->nullable();
            $table->timestamps();

            $table->foreign('verified_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_payment_submissions');
    }
};
