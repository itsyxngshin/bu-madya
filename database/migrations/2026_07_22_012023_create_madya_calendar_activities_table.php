<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('madya_calendar_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('activity_date');
            $table->string('category')->default('Event'); // e.g., Webinar, Deadline, Meeting
            $table->string('organizer')->nullable();
            $table->string('external_link')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('madya_calendar_activities');
    }
};
