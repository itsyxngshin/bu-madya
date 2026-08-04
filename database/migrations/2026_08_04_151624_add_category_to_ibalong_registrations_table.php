<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_registrations', function (Blueprint $table) {
            // Injects the category column. Defaults to 'General Classification' to prevent null errors on existing cohorts.
            $table->string('category')->default('General Classification')->after('affiliation');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_registrations', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
