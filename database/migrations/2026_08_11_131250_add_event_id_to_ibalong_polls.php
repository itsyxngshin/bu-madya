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
        Schema::table('ibalong_polls', function (Blueprint $table) {
            // Nullable because an 'Open' poll won't need an event linked to it
            $table->foreignId('event_id')->nullable()->constrained('ibalong_events')->nullOnDelete()->after('hackathon_id');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_polls', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
