<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* =========================================================
           PART 1: REFERENCE TABLES (No Foreign Keys)
           ========================================================= */

        Schema::create('ibalong_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_community_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_online_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2); // e.g., 25.50 for 25.5%
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        /* =========================================================
           PART 2: CORE REGISTRATION TABLES
           ========================================================= */

        // Note: Assuming you have a separate users table, if 'ibalong_users' is specific:
        /* =========================================================
           PART 2: CORE AUTHENTICATION & REGISTRATION TABLES
           ========================================================= */

        // 1. Unified Users Table (Created first so teams can link to it)
        Schema::create('ibalong_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('ibalong_roles')->cascadeOnDelete();
            
            // Core Authentication & Profile
            $table->string('name');
            $table->string('slug')->unique(); 
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar_path')->nullable();
            
            // Contact & Professional Identity
            $table->string('mobile_number')->nullable();
            $table->string('designation')->nullable(); 
            $table->text('bio')->nullable();
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            
            // System Tracking
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Team Registrations
        Schema::create('ibalong_registrations', function (Blueprint $table) {
            $table->id();
            
            // Link to the master Team Account (Nullable until admin approves)
            $table->foreignId('user_id')->nullable()->constrained('ibalong_users')->nullOnDelete();
            
            $table->string('team_name');
            $table->string('slug')->unique(); 
            $table->text('team_about');
            $table->string('affiliation');
            
            $table->unsignedBigInteger('province_id'); 
            $table->unsignedBigInteger('citymun_id');
            $table->unsignedBigInteger('barangay_id');
            
            $table->enum('team_member_demographics', ['YES', 'NO', 'NOT ALL BUT MAJORITY FROM BICOL']);
            $table->integer('number_of_team_members');
            $table->enum('onsite_commitment', ['YES', 'NO']);
            $table->enum('does_not_automatically_apply_clause', ['YES', 'NO']);
            $table->enum('selection_on_icp', ['YES', 'NO']);
            
            $table->boolean('media_consent')->default(false);
            $table->boolean('data_privacy_consent')->default(false);
            $table->enum('status', ['approved', 'pending', 'rejected', 'under review'])->default('pending');
            $table->string('account_creation_status')->nullable();
            $table->timestamps();
        });

        // 3. Team Members
        Schema::create('ibalong_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            
            // Link to individual member accounts (if you give them separate logins later)
            $table->foreignId('user_id')->nullable()->constrained('ibalong_users')->nullOnDelete();
            
            $table->string('full_name');
            $table->string('slug')->unique(); 
            $table->string('email_address');
            $table->string('mobile_number');
            $table->date('birthday');
            $table->string('course')->nullable();
            $table->string('role')->nullable();
            $table->string('position')->nullable();
            $table->string('affiliation');
            $table->enum('team_role', ['Team Leader', 'Team Member']);
            $table->timestamps();
        });

        /* =========================================================
           PART 3: PIVOT / RELATIONAL TABLES
           ========================================================= */

        Schema::create('ibalong_team_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('ibalong_skills')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('ibalong_team_member_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('ibalong_team_members')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('ibalong_skills')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('ibalong_team_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            $table->foreignId('experience_id')->constrained('ibalong_experiences')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_team_community_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            $table->foreignId('community_area_id')->constrained('ibalong_community_areas')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('ibalong_team_online_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('ibalong_registrations')->cascadeOnDelete();
            $table->foreignId('online_activity_id')->constrained('ibalong_online_activities')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop in reverse order to respect foreign key constraints
        Schema::dropIfExists('ibalong_team_online_participations');
        Schema::dropIfExists('ibalong_team_community_areas');
        Schema::dropIfExists('ibalong_team_experiences');
        Schema::dropIfExists('ibalong_team_member_skills');
        Schema::dropIfExists('ibalong_team_skills');
        Schema::dropIfExists('ibalong_team_members');
        Schema::dropIfExists('ibalong_registrations');
        Schema::dropIfExists('ibalong_users');
        Schema::dropIfExists('ibalong_criteria');
        Schema::dropIfExists('ibalong_online_activities');
        Schema::dropIfExists('ibalong_community_areas');
        Schema::dropIfExists('ibalong_experiences');
        Schema::dropIfExists('ibalong_skills');
        Schema::dropIfExists('ibalong_roles');
    }
}; 