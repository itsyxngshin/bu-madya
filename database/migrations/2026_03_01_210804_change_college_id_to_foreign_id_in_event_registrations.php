<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            // Drop the old string column
            $table->dropColumn('college_id');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            // Re-add it as a proper foreign key (nullable for non-students)
            $table->foreignId('college_id')->nullable()->after('classification')->constrained('colleges')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropForeign(['college_id']);
            $table->dropColumn('college_id');
            $table->string('college_id')->nullable()->after('classification');
        });
    }
};
