<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            
            // 1. Add election_id if it's missing
            if (!Schema::hasColumn('votes', 'election_id')) {
                $table->foreignId('election_id')->after('id')->constrained('elections')->cascadeOnDelete();
            }

            // 2. Add election_position_id if it's missing
            if (!Schema::hasColumn('votes', 'election_position_id')) {
                $table->foreignId('election_position_id')->after('election_id')->constrained('election_positions')->cascadeOnDelete();
            }

            // 3. Add candidate_id if it's missing
            if (!Schema::hasColumn('votes', 'candidate_id')) {
                $table->foreignId('candidate_id')->after('election_position_id')->constrained('candidates')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['election_id']);
            $table->dropForeign(['election_position_id']);
            $table->dropForeign(['candidate_id']);
            
            $table->dropColumn(['election_id', 'election_position_id', 'candidate_id']);
        });
    }
};