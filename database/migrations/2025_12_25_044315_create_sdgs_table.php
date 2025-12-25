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
        Schema::create('sdgs', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique(); // 1 to 17
            $table->string('name');              // e.g., "No Poverty"
            $table->string('slug');              // e.g., "no-poverty"
            $table->string('color_hex');         // e.g., "#E5243B"
            $table->string('icon_path')->nullable(); // Path to the image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_d_g_s');
    }
};
