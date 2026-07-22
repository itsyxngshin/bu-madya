<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('madya_calendar_activities', function (Blueprint $table) {
            $table->renameColumn('activity_date', 'start_date');
            $table->date('end_date')->after('activity_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('madya_calendar_activities', function (Blueprint $table) {
            $table->renameColumn('start_date', 'activity_date');
            $table->dropColumn('end_date');
        });
    }
};
