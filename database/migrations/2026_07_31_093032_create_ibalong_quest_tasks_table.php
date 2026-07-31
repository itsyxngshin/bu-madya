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
        Schema::create('ibalong_quest_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('ibalong_quests')->onDelete('cascade');
            $table->string('question'); // e.g., "Upload your Anticipatory Action wireframes"
            $table->enum('type', ['short_text', 'long_text', 'dropdown', 'checklist', 'file']);
            $table->json('options')->nullable(); // For dropdown/checklist choices
            $table->integer('max_file_size_mb')->nullable(); // Limit for file uploads
            $table->boolean('is_required')->default(true);
            $table->integer('order_index')->default(0); // To sort the questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibalong_quest_tasks');
    }
};
