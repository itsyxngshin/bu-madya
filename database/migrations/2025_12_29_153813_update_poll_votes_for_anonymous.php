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
        Schema::table('poll_votes', function (Blueprint $table) {
            // 1. Make user_id optional
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // 2. Add tracking for guests
            $table->string('session_id')->nullable()->after('user_id');
            $table->string('ip_address')->nullable()->after('session_id'); // Optional extra security
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
