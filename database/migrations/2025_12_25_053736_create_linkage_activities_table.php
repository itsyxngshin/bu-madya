<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('linkage_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('linkage_id')->constrained('linkages')->onDelete('cascade');
            $table->string('title');       // e.g., "MOU Signing Ceremony"
            $table->date('activity_date'); 
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable(); // Proof of engagement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linkage_activities');
    }
};
