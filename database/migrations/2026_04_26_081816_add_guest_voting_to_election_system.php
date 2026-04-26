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
            
            // A. Drop the Foreign Keys FIRST (Moving the furniture)
            $table->dropForeign(['user_id']);
            $table->dropForeign(['election_id']);

            // B. NOW you can safely drop the unique constraint (Pulling the rug)
            $table->dropUnique(['user_id', 'election_id']);

            // C. Make user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // D. Add the Guest Identity & Demographic Fields
            $table->string('guest_email')->nullable()->after('user_id'); 
            $table->string('guest_name')->nullable()->after('guest_email');
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete()->after('guest_name');
            $table->string('program')->nullable()->after('college_id');
            $table->string('year_level')->nullable()->after('program');

            // E. Re-apply unique constraints
            $table->unique(['user_id', 'election_id']); 
            $table->unique(['guest_email', 'election_id']); 

            // F. Re-attach the Foreign Keys (Putting the furniture back)
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('election_id')->references('id')->on('elections')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Reversal logic
    }
};