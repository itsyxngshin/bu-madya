<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Poll Configuration
        Schema::create('ibalong_polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained('ibalong_hackathons')->cascadeOnDelete();
            $table->string('title')->default("People's Choice Award");
            $table->boolean('is_active')->default(false); // Master switch to open/close voting
            $table->boolean('require_ticket')->default(true); // Toggle: Open to public vs. Ticket Code required
            $table->timestamps();
        });

        // 2. The Vote Ledger (Hooked into existing Event Tickets)
        Schema::create('ibalong_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('ibalong_polls')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();

            // Stores the existing event ticket code. Nullable for 'Open' voting.
            $table->string('ticket_code')->nullable();

            // Helpful for basic spam mitigation if the poll is set to "Open"
            $table->string('ip_address')->nullable();

            $table->timestamps();

            // Ensure a specific ticket code can only ever cast one vote per poll
            $table->unique(['poll_id', 'ticket_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_votes');
        Schema::dropIfExists('ibalong_polls');
    }
};
