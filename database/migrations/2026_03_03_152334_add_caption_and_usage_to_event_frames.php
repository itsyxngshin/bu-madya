<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_frames', function (Blueprint $table) {
            $table->longText('caption')->nullable()->after('description');
            $table->unsignedInteger('usage_count')->default(0)->after('caption');
        });
    }

    public function down(): void
    {
        Schema::table('event_frames', function (Blueprint $table) {
            $table->dropColumn(['caption', 'usage_count']);
        });
    }
};