<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add the Toggle to the Election
        Schema::table('elections', function (Blueprint $table) {
            $table->boolean('allow_guest_voting')->default(false)->after('status');
        });

        // 2. Upgrade the Voter Logs table for Guests
        Schema::table('voter_logs', function (Blueprint $table) {
            // Drop the old strict user_id constraint first
            $table->dropUnique(['user_id', 'election_id']);

            // Make user_id nullable for guests
            $table->foreignId('user_id')->nullable()->change();

            // Add the Guest Identity & Demographic Fields
            $table->string('guest_email')->nullable()->after('user_id'); // We use email to prevent double guest voting
            $table->string('guest_name')->nullable()->after('guest_email');
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete()->after('guest_name');
            $table->string('program')->nullable()->after('college_id');
            $table->string('year_level')->nullable()->after('program');

            // Re-apply unique constraints
            // MySQL allows multiple NULLs in unique indexes, so this is perfectly safe!
            $table->unique(['user_id', 'election_id']); 
            $table->unique(['guest_email', 'election_id']); // Ensures a guest email can only vote once per election!
        });
    }

    public function down(): void
    {
        // Reversal logic
    }
};