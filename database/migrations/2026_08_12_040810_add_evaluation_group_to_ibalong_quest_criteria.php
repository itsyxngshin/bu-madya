<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_quest_criteria', function (Blueprint $table) {
            // Allows you to label criteria as "Main Matrix", "Pitch Rubric", etc.
            $table->string('evaluation_group')->default('Main Scoring Matrix')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_quest_criteria', function (Blueprint $table) {
            $table->dropColumn('evaluation_group');
        });
    }
};
