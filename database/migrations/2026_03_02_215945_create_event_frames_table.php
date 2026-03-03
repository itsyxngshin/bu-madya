<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_frames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The org/user who requested it
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete(); // Optional link to an event
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_approved')->default(false); // Admin approval required
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_frames');
    }
};
