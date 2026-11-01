<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('hosting_payment_submissions')->cascadeOnDelete();
            
            $table->string('file_path');
            $table->string('storage_disk')->default('private');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size_bytes');
            $table->string('file_sha256', 64)->index(); // Fraud & duplicate slip detection
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_payment_proofs');
    }
};
