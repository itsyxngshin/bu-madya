<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_activities', function (Blueprint $table) {
            $table->boolean('allow_booking')->default(true)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_activities', function (Blueprint $table) {
            $table->dropColumn('allow_booking');
        });
    }
};
