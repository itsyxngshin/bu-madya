<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pillars', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. "Campus Safety Reform"
            $table->text('description')->nullable(); // Context
            $table->string('image_path')->nullable(); // Cover Image
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 2. THE QUESTIONS (e.g. "Do you support CCTV?", "Should we increase guards?")
        Schema::create('pillar_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pillar_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->timestamps();
        });

        // 3. THE OPTIONS (Choices per question)
        Schema::create('pillar_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pillar_question_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // Yes, No, Maybe
            $table->string('color')->default('gray'); // For UI bars
            $table->timestamps();
        });

        // 4. THE VOTES
        Schema::create('pillar_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pillar_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pillar_option_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable(); // Nullable for guests
            $table->string('session_id')->nullable(); // For guest tracking
            $table->timestamps();

            // Prevent double voting on a specific question by User OR Session
            // Note: DB unique constraints for nulls vary by engine, so we enforce logic in Livewire mostly.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pillar_votes');
        Schema::dropIfExists('pillar_options');
        Schema::dropIfExists('pillar_questions');
        Schema::dropIfExists('pillars');
    }
};
