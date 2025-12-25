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
        Schema::create('linkage_projects', function (Blueprint $table) {
            $table->id();
            
            // The Partner
            $table->foreignId('linkage_id')
                  ->constrained('linkages')
                  ->onDelete('cascade');

            // The Project
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->onDelete('cascade');

            // Optional: What did they do? 
            // e.g. "Main Sponsor", "Venue Partner", "Co-Implementer"
            $table->string('role')->nullable()->default('Partner');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linkage_projects');
    }
};
