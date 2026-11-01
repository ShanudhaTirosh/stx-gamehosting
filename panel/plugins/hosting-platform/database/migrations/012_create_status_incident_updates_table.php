<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_incident_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_incident_id')->constrained('status_incidents')->cascadeOnDelete();
            $table->string('status'); // investigating, identified, monitoring, resolved, update
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_incident_updates');
    }
};
