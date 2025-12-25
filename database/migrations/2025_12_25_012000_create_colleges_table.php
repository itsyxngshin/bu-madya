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
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            $table->id();
            $table->string('name'); // e.g., "College of Science"
            $table->string('slug')->unique(); // e.g., "cs"
            
            // Optional: You might want to add a logo or color for the college later
            $table->string('logo_path')->nullable(); 
            $table->string('color_code')->nullable(); // e.g., "#FF0000"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
};
