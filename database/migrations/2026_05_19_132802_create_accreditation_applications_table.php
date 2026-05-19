<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accreditation_applications', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();

            $table->enum('application_type', ['accreditation', 'reaccreditation']);
            $table->enum('status', ['draft', 'pending', 'under_review', 'approved', 'returned'])->default('draft');

            // Bank Details
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();

            // Add these inside the accreditation_applications migration:

            // President / Chairman Details (Prepared By)
            $table->string('president_name')->nullable();
            $table->string('president_contact')->nullable();
            $table->string('president_email')->nullable();
            $table->string('president_signature_path')->nullable(); // Stores the uploaded e-signature

            // Adviser Details
            $table->string('adviser_name')->nullable();
            $table->string('adviser_contact')->nullable();
            $table->string('adviser_email')->nullable();
            $table->string('adviser_signature_path')->nullable(); // Stores the uploaded e-signature

            // Digital Routing / Approval Tracking (Bottom of page 2)
            $table->enum('adviser_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('committee_type', ['CBO', 'UBO'])->nullable(); // College-Based or University-Based
            $table->string('committee_approval_status')->default('pending');
            // Document Storage Paths
            $table->string('bankbook_photo_path')->nullable();
            $table->string('cbl_path')->nullable();
            $table->string('recent_fliers_path')->nullable();

            // Reaccreditation Specific Documents
            $table->string('accomplishment_report_path')->nullable();
            $table->string('audited_financial_report_path')->nullable();

            $table->timestamps();

            // Prevent an organization from applying twice for the same academic year
            $table->unique(['organization_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_applications');
    }
};
