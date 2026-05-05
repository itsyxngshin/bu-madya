<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // The specific day of the log
            $table->date('log_date');

            // The 4-Punch System
            $table->timestamp('morning_in')->nullable();
            $table->timestamp('morning_out')->nullable();
            $table->timestamp('afternoon_in')->nullable();
            $table->timestamp('afternoon_out')->nullable();

            // Storing the calculation (easier for dashboard querying than calculating on the fly)
            $table->integer('total_minutes_rendered')->default(0);

            // e.g., 'present', 'absent', 'excused', 'holiday'
            $table->string('status')->default('present');

            $table->timestamps();

            // Ensure only one log entry per user, per day
            $table->unique(['user_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};
