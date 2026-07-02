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
        Schema::create('announcement_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Emergency', 'Urgent', 'DRR', 'General'
            $table->string('slug')->unique(); // e.g., 'emergency'
            $table->string('color_theme'); // e.g., 'bg-red-600 text-white'
            $table->text('icon_svg')->nullable(); // Store the raw SVG path data here
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_types');
    }
};
