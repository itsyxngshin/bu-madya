<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elucida_signatories', function (Blueprint $table) {
            if (!Schema::hasColumn('elucida_signatories', 'order_sequence')) {
                $table->integer('order_sequence')->default(1)->after('signature_path');
            }
            if (!Schema::hasColumn('elucida_signatories', 'has_signed')) {
                $table->boolean('has_signed')->default(false)->after('position');
            }
            if (!Schema::hasColumn('elucida_signatories', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('has_signed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('elucida_signatories', function (Blueprint $table) {
            $table->dropColumn(['order_sequence', 'has_signed', 'signed_at']);
        });
    }
};
