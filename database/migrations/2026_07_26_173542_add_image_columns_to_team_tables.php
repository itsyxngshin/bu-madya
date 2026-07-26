<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_registrations', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('team_name');
        });

        // Assuming your members table is named ibalong_team_members
        Schema::table('ibalong_team_members', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('full_name');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_registrations', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });

        Schema::table('ibalong_team_members', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};