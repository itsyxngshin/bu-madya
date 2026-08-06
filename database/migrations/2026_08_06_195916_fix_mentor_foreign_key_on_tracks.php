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
        Schema::table('ibalong_activity_tracks', function (Blueprint $table) {
            // Drop the old incorrect constraint
            $table->dropForeign('ibalong_activity_tracks_mentor_id_foreign');

            // Re-link it to the correct Ibalong users table
            $table->foreign('mentor_id')
                  ->references('id')->on('ibalong_users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
