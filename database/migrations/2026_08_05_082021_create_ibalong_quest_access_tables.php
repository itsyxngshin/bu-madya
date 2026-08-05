<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_quests', function (Blueprint $table) {
            $table->boolean('is_restricted')->default(false)->after('is_published');
        });

        // 2. Forge the pivot table for cohort clearances
        Schema::create('ibalong_quest_team_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('ibalong_quests')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            $table->timestamps();
            
            // Prevent duplicate clearance records
            $table->unique(['quest_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_quest_team_access');
        Schema::table('ibalong_quests', function (Blueprint $table) {
            $table->dropColumn('is_restricted');
        });
    }
};