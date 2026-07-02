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
        Schema::create('spotlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spotlight_category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('image_path');
            $table->string('link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            // Add these two scheduling columns
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spotlights');
    }
};
