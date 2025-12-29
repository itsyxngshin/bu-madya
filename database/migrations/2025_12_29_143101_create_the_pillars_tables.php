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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // e.g. "Pillar 1: Quality Education"
            $table->text('question'); // e.g. "Should we prioritize AI integration?"
            $table->text('description')->nullable(); // Context/Details
            $table->string('image_path')->nullable(); // Cover image
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 2. The Options (Choices)
        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // e.g. "Yes", "No", "Abstain"
            $table->string('color')->default('gray'); // For UI: 'red', 'green', 'yellow'
            $table->timestamps();
        });

        // 3. The Votes (Ledger)
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Logged-in users only
            $table->timestamps();

            // Prevent double voting per poll
            $table->unique(['poll_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_votes'); 
        Schema::dropIfExists('poll_options'); 
        Schema::dropIfExists('polls');
    }
};
