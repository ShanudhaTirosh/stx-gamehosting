<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('incident'); // incident, maintenance
            $table->string('status')->default('investigating'); // investigating, identified, monitoring, resolved, scheduled, in_progress, completed
            $table->string('impact')->default('minor'); // none, minor, major, critical
            $table->json('affected_monitors')->nullable(); // array of monitor IDs or names
            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('scheduled_end')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_incidents');
    }
};
