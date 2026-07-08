<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_committee_members', function (Blueprint $table) {
            // Records the True/False state of the tick box
            $table->boolean('devcon_consent')->default(false)->after('motivation');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_committee_members', function (Blueprint $table) {
            $table->dropColumn('devcon_consent');
        });
    }
};