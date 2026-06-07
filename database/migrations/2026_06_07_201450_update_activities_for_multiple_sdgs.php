<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Drop the old single ID column
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['sdg_id']);
            $table->dropColumn('sdg_id');
        });

        // 2. Create the Many-to-Many Pivot Table
        Schema::create('activity_sdg', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sdg_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_sdg');
    }
};
