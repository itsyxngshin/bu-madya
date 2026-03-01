<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('classification')->default('BU Student')->after('email');
            $table->string('college_id')->nullable()->after('classification');
            $table->string('program')->nullable()->after('college_id');
            $table->string('year_level')->nullable()->after('program');
            $table->string('organization_name')->nullable()->after('year_level');
            $table->string('position')->nullable()->after('organization_name');
        });
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['classification', 'college_id', 'program', 'year_level', 'organization_name', 'position']);
        });
    }
};