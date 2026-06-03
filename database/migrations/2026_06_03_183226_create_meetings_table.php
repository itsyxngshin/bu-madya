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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            // NEW: Academic Year Link
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->date('meeting_date');
            $table->time('start_time');
            $table->string('location')->nullable();
            $table->text('agenda')->nullable();
            $table->longText('minutes')->nullable();
            $table->enum('status', ['scheduled', 'completed'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
