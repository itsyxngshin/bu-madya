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
        // 1. Types (Government, NGO, etc.)
        Schema::create('linkage_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Display Name
            $table->string('slug')->unique(); // For reference/classes
            $table->string('color')->nullable(); // For badges
            $table->timestamps();
        });

        // 2. Statuses (Active, Inactive, etc.)
        Schema::create('linkage_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->nullable(); // e.g., 'bg-green-100'
            $table->timestamps();
        });

        // 3. Agreement Levels (MOU, MOA, etc.)
        Schema::create('agreement_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique(); 
            $table->string('description')->nullable(); // Explains what this level means
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linkage_types');
        Schema::dropIfExists('linkage_statuses');
        Schema::dropIfExists('agreement_levels');
    }
};
