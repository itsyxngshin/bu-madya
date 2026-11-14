<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_liquidations', function (Blueprint $table) {
            // Drop the strict ID requirement since the form uses manual text names
            $table->dropForeign(['accountable_officer_id']);
            $table->dropColumn('accountable_officer_id');

            // Add the new text columns sent by the controller
            if (!Schema::hasColumn('elucida_liquidations', 'accountable_officer')) {
                $table->string('accountable_officer')->nullable()->after('dv_number');
            }
            if (!Schema::hasColumn('elucida_liquidations', 'officer_position')) {
                $table->string('officer_position')->nullable()->after('accountable_officer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('elucida_liquidations', function (Blueprint $table) {
            $table->dropColumn(['accountable_officer', 'officer_position']);
            $table->foreignId('accountable_officer_id')->nullable()->constrained('users');
        });
    }
};
