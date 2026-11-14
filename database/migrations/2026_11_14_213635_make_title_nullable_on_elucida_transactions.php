<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_transactions', function (Blueprint $table) {
            // Checks if the column exists, then modifies it to be nullable
            if (Schema::hasColumn('elucida_transactions', 'title')) {
                $table->string('title')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('elucida_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('elucida_transactions', 'title')) {
                // Reverting it back to strictly required
                $table->string('title')->nullable(false)->change();
            }
        });
    }
};
