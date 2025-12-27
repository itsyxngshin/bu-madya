<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            
            // 1. Ownership
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // The author
            $table->string('name')->nullable(); // For anonymous proposals
            $table->string('email')->nullable(); // For anonymous proposals
            $table->foreignId('college_id')->constrained()->nullable(); 
            $table->enum('proponent_type', ['BU Student', 'Community Stakeholder', 'NGO', 'Faculty'])->default('BU Student');
            $table->enum('status', ['draft', 'pending review', 'approved', 'rejected', 'returned'])->default('draft');

            // 3. Core Content (Nullable because they fill it step-by-step)
            $table->string('title')->nullable();
            $table->string('project_type')->nullable(); // e.g. "Outreach", "Seminar"
            $table->text('rationale')->nullable();
            $table->string('potential_partners')->nullable();

            // 4. Logistics
            $table->enum('modality', ['online', 'onsite'])->default('onsite');
            $table->string('venue')->nullable();
            $table->date('target_start_date')->nullable();
            $table->date('target_end_date')->nullable();
            $table->string('target_beneficiaries')->nullable(); // e.g. "50 Students"
            
            // 5. Financials
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->text('budget_description')->nullable(); // "Please revise the budget part..."
            // 7. Admin Feedback
            $table->text('admin_remarks')->nullable(); // "Please revise the budget part..."

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proposals');
    }
};