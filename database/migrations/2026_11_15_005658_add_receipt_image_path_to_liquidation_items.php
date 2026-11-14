<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_liquidation_items', function (Blueprint $table) {
            // Adds the missing column that the controller is trying to populate
            if (!Schema::hasColumn('elucida_liquidation_items', 'receipt_image_path')) {
                $table->string('receipt_image_path')->nullable()->after('amount');
            }

            // Relaxes the date constraint to prevent empty string crashes
            if (Schema::hasColumn('elucida_liquidation_items', 'date_incurred')) {
                $table->date('date_incurred')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('elucida_liquidation_items', function (Blueprint $table) {
            $table->dropColumn('receipt_image_path');
        });
    }
};
