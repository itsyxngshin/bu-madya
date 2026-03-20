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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            
            // The unique tracker ID (e.g., CASE-0006)
            $table->string('case_number')->unique();
            
            // Reporter Details
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('year_and_block');
            
            // Incident Details
            $table->string('nature_of_incident'); // Bullying, Harassment, etc.
            $table->text('incident_details');
            $table->string('file_upload_path')->nullable(); // For attached evidence
            
            // Tracking Status
            $table->string('status')->default('Pending'); // Pending, Under Review, Resolved
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
