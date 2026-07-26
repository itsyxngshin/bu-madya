<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_notifications', function (Blueprint $table) {
            // 1. Drop the incorrect foreign key constraint
            // (Using the array syntax allows Laravel to automatically resolve the constraint name)
            $table->dropForeign(['user_id']); 

            // 2. Re-establish the foreign key pointing to the correct ibalong_users table
            $table->foreign('user_id')
                  ->references('id')
                  ->on('ibalong_users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_notifications', function (Blueprint $table) {
            // Revert back if we ever need to rollback
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};