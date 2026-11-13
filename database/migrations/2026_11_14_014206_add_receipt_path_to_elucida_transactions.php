<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('elucida_transactions', 'receipt_image_path')) {
                $table->string('receipt_image_path')->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('elucida_transactions', function (Blueprint $table) {
            $table->dropColumn('receipt_image_path');
        });
    }
};
