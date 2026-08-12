<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_polls', function (Blueprint $table) {
            // Stores the IDs of the eligible teams
            $table->json('nominee_ids')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_polls', function (Blueprint $table) {
            $table->dropColumn('nominee_ids');
        });
    }
};
