<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('hosting_products')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('tagline')->nullable();
            
            // Pricing in Sri Lankan Rupees (LKR)
            $table->decimal('price_lkr', 10, 2);
            $table->decimal('setup_fee_lkr', 10, 2)->default(0.00);
            $table->string('billing_period')->default('monthly');

            // Resource quotas
            $table->integer('memory_mb')->default(1024);
            $table->integer('cpu_percent')->default(100);
            $table->integer('disk_mb')->default(10240);
            $table->integer('swap_mb')->default(0);
            $table->integer('io_weight')->default(500);

            // Server feature limits
            $table->integer('database_limit')->default(1);
            $table->integer('allocation_limit')->default(1);
            $table->integer('backup_limit')->default(2);

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_plans');
    }
};
