<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->timestamp('voted_at');
            $table->timestamps();
            
            // Prevents double-voting
            $table->unique(['user_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_logs');
    }
};