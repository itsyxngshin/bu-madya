<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add fields to existing events table
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_internal_rsvp')->default(false)->after('cover_image'); // Toggles Luma-style vs External Link
            $table->string('location')->nullable()->after('end_date');
            $table->integer('capacity')->nullable()->after('location');
        });

        // 2. Create the registrations table
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained(); // Nullable for guest students
            $table->string('name');
            $table->string('email');
            $table->string('ticket_code')->unique();
            $table->enum('status', ['Registered', 'Attended', 'Cancelled'])->default('Registered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['is_internal_rsvp', 'location', 'capacity']);
        });
    }
};
