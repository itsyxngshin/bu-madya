<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_ledgers', function (Blueprint $table) {
            // Drop the old string column if it exists
            if (Schema::hasColumn('elucida_ledgers', 'organization_name')) {
                $table->dropColumn('organization_name');
            }

            // Add the strict relationship column
            $table->foreignId('user_id')->nullable()->after('period_name')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('elucida_ledgers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->string('organization_name')->nullable();
        });
    }
};
