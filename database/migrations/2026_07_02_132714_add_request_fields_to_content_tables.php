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
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('status')->default('approved'); // 'pending', 'approved', 'rejected'
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who requested it
            $table->text('admin_remarks')->nullable(); // Reason for rejection or admin notes
        });

        Schema::table('spotlights', function (Blueprint $table) {
            $table->string('status')->default('approved'); // 'pending', 'approved', 'rejected'
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('admin_remarks')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status', 'user_id', 'admin_remarks']);
        });

        Schema::table('spotlights', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['status', 'user_id', 'admin_remarks']);
        });
    }
};
