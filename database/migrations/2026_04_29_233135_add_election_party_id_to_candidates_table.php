<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            // Adds the column right after election_position_id to keep the DB tidy
            $table->foreignId('election_party_id')
                  ->nullable()
                  ->after('election_position_id')
                  ->constrained('election_parties')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['election_party_id']);
            $table->dropColumn('election_party_id');
        });
    }
};
