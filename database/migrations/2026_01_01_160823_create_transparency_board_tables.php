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
        // 1. Create Categories Table First (Parent)
        Schema::create('transparency_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // e.g. "Financial Report"
            $table->string('slug')->unique();       // e.g. "financial-report"
            $table->string('color')->default('gray'); // e.g. "red", "blue", "yellow"
            $table->timestamps();
        });

        // 2. Create Documents Table (Child)
        Schema::create('transparency_documents', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key to Categories
            $table->foreignId('category_id')
                  ->constrained('transparency_categories')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');            // Path to PDF or Image
            $table->string('academic_year');        // e.g. "2025-2026"
            $table->string('visibility')->default('public'); 
            $table->date('published_date');
            
            // Analytics
            $table->unsignedBigInteger('downloads_count')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transparency_documents');
        Schema::dropIfExists('transparency_categories');
    }
};
