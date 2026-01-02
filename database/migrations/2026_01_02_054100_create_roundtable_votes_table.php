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
        Schema::create('roundtable_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('roundtable_reply_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('vote'); // 1 for Upvote, -1 for Downvote
            $table->timestamps();
            
            // Ensure a user can only vote once per reply
            $table->unique(['user_id', 'roundtable_reply_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roundtable_votes');
    }
};
