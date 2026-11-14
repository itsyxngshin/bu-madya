<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_transactions', function (Blueprint $table) {
            // Adds the missing reference_code column safely
            if (!Schema::hasColumn('elucida_transactions', 'reference_code')) {
                $table->string('reference_code')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('elucida_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('elucida_transactions', 'reference_code')) {
                $table->dropColumn('reference_code');
            }
        });
    }
};
