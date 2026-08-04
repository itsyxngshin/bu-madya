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
        Schema::table('ibalong_evaluations', function (Blueprint $table) {
            // 'public' (everyone) or 'teams_only' (registered cohorts)
            $table->string('access_level')->default('public')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_evaluations', function (Blueprint $table) {
            $table->dropColumn('access_level');
        });
    }

};
