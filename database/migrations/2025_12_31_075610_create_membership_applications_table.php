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
        Schema::create('membership_applications', function (Blueprint $table) {
            $table->id();
            
            // A. Consent
            $table->boolean('privacy_consent')->default(false);
            $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
            $table->foreignId('committee_1_id')->constrained('committees')->onDelete('cascade');
            $table->foreignId('committee_2_id')->constrained('committees')->onDelete('cascade');
            $table->foreignId('membership_wave_id')->nullable()->constrained();
            
            // B. Personal Details
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->text('home_address');
            $table->date('birthday');
            $table->string('contact_number');
            $table->string('email'); // Can add ->unique() if you want strict checking
            $table->string('facebook_link')->nullable();
            
            // C. Academic Details
            $table->string('year_level');
            $table->string('course');
            
            // D. Organization & Political
            $table->text('political_affiliation')->nullable(); // "None" or specifics
            
            // E. Files (Paths)
            $table->string('photo_path');
            $table->string('signature_path');
            
            // F. Essay Questions
            $table->text('essay_1_community');
            $table->text('essay_2_action');
            $table->text('essay_3_experience')->nullable();
            $table->text('essay_4_reason');
            $table->text('essay_5_suggestion')->nullable();
            
            // G. Payment
            $table->boolean('willing_to_pay')->default(false);

            // H. System Status
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_applications');
    }
};
