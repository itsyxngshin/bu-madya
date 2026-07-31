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
        Schema::create('ibalong_quest_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('ibalong_quest_submissions')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('ibalong_quest_tasks')->onDelete('cascade');
            $table->text('answer_text')->nullable(); // Text, Dropdown selection, or JSON array for checklist
            $table->string('file_path')->nullable(); // Path if it's a file upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibalong_quest_answers');
    }
};
