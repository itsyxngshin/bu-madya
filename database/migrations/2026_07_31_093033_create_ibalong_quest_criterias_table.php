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
        Schema::create('ibalong_quest_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('ibalong_quests')->onDelete('cascade');
            $table->string('name'); // e.g., "Innovation & Impact", "Technical Feasibility"
            $table->integer('max_score'); // e.g., 10, 20, 50
            $table->text('description')->nullable();
            $table->json('rubric_levels')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibalong_quest_criterias');
    }
};
