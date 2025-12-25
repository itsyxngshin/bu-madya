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
       Schema::create('linkage_sdgs', function (Blueprint $table) {
            $table->id();
            
            // Connect Linkage (Partner)
            $table->foreignId('linkage_id')
                  ->constrained('linkages')
                  ->onDelete('cascade');
            
            // Connect SDG
            // Note: Explicitly pointing to 'sdgs' table just to be safe
            $table->foreignId('sdg_id')
                  ->constrained('sdgs') 
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linkage_sdgs');
    }
};
