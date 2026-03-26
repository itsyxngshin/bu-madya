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
        Schema::create('event_collaborators', function (Blueprint $table) {
            $table->id();
            // Link to the event (if event is deleted, remove collaborators)
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            // Link to the user who is granted access
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            // Prevent the exact same user from being added to the same event twice
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_collaborators');
    }
};
