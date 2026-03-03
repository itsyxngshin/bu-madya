<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_frames', function (Blueprint $table) {
            // Add a JSON column to hold the array of image paths
            $table->json('frame_images')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('event_frames', function (Blueprint $table) {
            $table->dropColumn('frame_images');
        });
    }
};