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
        Schema::table('incident_reports', function (Blueprint $table) {
            // We make it nullable so students can still choose to send it to the "Main STRAW Office" instead of a specific org
            $table->foreignId('assigned_org_id')->nullable()->constrained('users')->nullOnDelete()->after('case_number');
        });
    }

    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropForeign(['assigned_org_id']);
            $table->dropColumn('assigned_org_id');
        });
    }
};
