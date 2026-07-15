<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Events Table
        Schema::create('ibalong_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['Online', 'Physical', 'Hybrid'])->default('Physical');
            $table->string('venue_or_link')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->integer('max_capacity')->nullable(); // Null means unlimited
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. The Registrations (Tickets) Table
        Schema::create('ibalong_event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('ibalong_events')->cascadeOnDelete();

            // Link directly to your existing Hackathon Teams table
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('ibalong_registrations')->nullOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('affiliation')->nullable();
            $table->enum('role', ['Audience', 'Team Member', 'Facilitator', 'VIP'])->default('Audience');
            $table->string('ticket_code')->unique();
            $table->enum('status', ['Pending', 'Approved', 'Cancelled'])->default('Approved');
            $table->timestamps();
        });

        // 3. The Attendance Tracking Table
        Schema::create('ibalong_event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('ibalong_event_registrations')->cascadeOnDelete();
            $table->timestamp('scanned_at');
            $table->string('scanned_by')->nullable(); // To record which admin terminal scanned the ticket
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_event_attendances');
        Schema::dropIfExists('ibalong_event_registrations');
        Schema::dropIfExists('ibalong_events');
    }
};
