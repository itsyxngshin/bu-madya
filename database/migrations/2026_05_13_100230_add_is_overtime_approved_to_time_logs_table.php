<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->boolean('is_overtime_approved')->default(false)->after('total_minutes_rendered');
        });
    }

    public function down()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->dropColumn('is_overtime_approved');
        });
    }
};
