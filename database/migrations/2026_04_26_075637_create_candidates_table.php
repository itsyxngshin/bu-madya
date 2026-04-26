<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->foreignId('college_id')->constrained()->cascadeOnDelete();
            $table->foreignId('election_position_id')->constrained()->cascadeOnDelete();
            
            $table->string('program');
            $table->string('year_level');
            $table->text('address');
            $table->string('profile_photo_path')->nullable();
            $table->string('e_signature_path')->nullable();
            
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            
            // Prevents a student from applying twice to the same election
            $table->unique(['user_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};