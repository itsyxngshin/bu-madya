<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Questions Table (Linked to the Event)
        Schema::create('event_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->text('question_text'); // e.g., "Do you agree to the Data Privacy Act?"
            $table->string('type')->default('checkbox'); // 'checkbox', 'text', 'select'
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        // 2. The Answers Ledger (Linked to the Registration Ticket)
        Schema::create('event_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('event_registrations')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('event_questions')->cascadeOnDelete();
            $table->text('answer')->nullable(); // "Yes", "No", or typed text
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_answers');
        Schema::dropIfExists('event_questions');
    }
};
