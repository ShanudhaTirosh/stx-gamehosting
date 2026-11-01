<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('hosting_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();

            // Egg & Deployment binding
            $table->unsignedInteger('egg_id')->nullable();
            $table->string('docker_image')->nullable();
            $table->text('startup_command')->nullable();
            $table->json('default_environment')->nullable();
            $table->json('deployment_tags')->nullable();
            $table->json('ports')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('egg_id')->references('id')->on('eggs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_products');
    }
};
