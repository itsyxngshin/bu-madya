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
        Schema::create('ibalong_quest_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('ibalong_quest_submissions')->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('ibalong_users')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('ibalong_quest_criteria')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('feedback')->nullable();
            $table->timestamps();

            // A judge can only score a specific criteria for a specific submission once
            $table->unique(['submission_id', 'judge_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibalong_quest_scores');
    }
};
