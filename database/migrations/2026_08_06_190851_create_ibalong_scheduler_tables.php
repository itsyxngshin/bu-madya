<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Multi-Tenant Hackathon Container
        Schema::create('ibalong_hackathons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('status')->default('active'); // active, archived, drafting
            $table->timestamps();
        });

        // 2. The Global Activity (e.g. "Phase 1 Mentorship")
        Schema::create('ibalong_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained('ibalong_hackathons')->cascadeOnDelete();
            $table->string('title');
            $table->string('type'); // mentorship, cliniquing, pitch_practice
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        // 3. The Specific Hubs/Tracks inside the Activity
        Schema::create('ibalong_activity_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('ibalong_activities')->cascadeOnDelete();
            $table->string('name'); // e.g., "Tech Validation Hub"
            $table->unsignedBigInteger('mentor_id')->nullable(); // Links to your users table
            $table->string('location')->nullable(); // Zoom link or physical room
            $table->timestamps();

            $table->foreign('mentor_id')->references('id')->on('users')->nullOnDelete();
        });

        // 4. The Time Slots generated for each Hub
        Schema::create('ibalong_activity_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained('ibalong_activity_tracks')->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('capacity')->default(1); // How many teams can book this exact slot
            $table->boolean('is_locked')->default(false); // Admin override to block a slot
            $table->timestamps();
        });

        // 5. The Team Appointments
        Schema::create('ibalong_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('ibalong_activity_slots')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            $table->string('status')->default('booked'); // booked, attended, no_show, cancelled
            $table->text('notes')->nullable(); // Optional feedback from mentor
            $table->timestamps();

            // Prevent a team from booking the exact same slot twice
            $table->unique(['slot_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_appointments');
        Schema::dropIfExists('ibalong_activity_slots');
        Schema::dropIfExists('ibalong_activity_tracks');
        Schema::dropIfExists('ibalong_activities');
        Schema::dropIfExists('ibalong_hackathons');
    }
};