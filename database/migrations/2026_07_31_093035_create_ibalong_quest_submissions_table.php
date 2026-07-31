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
        Schema::create('ibalong_quest_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('ibalong_quests')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('ibalong_registrations')->onDelete('cascade');
            $table->enum('status', ['draft', 'submitted', 'reviewing', 'reviewed'])->default('draft');
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();

            // A team can only have one submission per quest
            $table->unique(['quest_id', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibalong_quest_submissions');
    }
};
