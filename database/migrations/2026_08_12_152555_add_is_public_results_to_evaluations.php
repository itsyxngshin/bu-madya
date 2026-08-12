<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Target the correct Ibalong table and remove the strict placement anchor
        Schema::table('ibalong_evaluations', function (Blueprint $table) {
            $table->boolean('is_public_results')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_evaluations', function (Blueprint $table) {
            $table->dropColumn('is_public_results');
        });
    }
};
