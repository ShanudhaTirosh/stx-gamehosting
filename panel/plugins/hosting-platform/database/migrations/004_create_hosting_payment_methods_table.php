<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // bank_transfer, ez_cash, helapay
            $table->string('type'); // matches PaymentMethodType enum
            $table->text('description')->nullable();
            
            // Administrative Payment Instructions
            $table->string('recipient_name')->nullable();
            $table->string('account_or_mobile_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->text('payment_instructions');
            $table->string('qr_code_image')->nullable();
            
            $table->decimal('min_amount_lkr', 10, 2)->default(0.00);
            $table->decimal('max_amount_lkr', 10, 2)->nullable();
            $table->boolean('requires_proof_upload')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_payment_methods');
    }
};
