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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The author
            
            // 2. Proposal Progress (For the "Builder" wizard)
            // e.g. 1 = Basic Info, 2 = Objectives, 3 = Budget, 4 = Review
            $table->integer('current_step')->default(1); 
            $table->enum('status', ['draft', 'pending review', 'approved', 'rejected', 'returned'])->default('draft');

            // 3. Core Content (Nullable because they fill it step-by-step)
            $table->string('title')->nullable();
            $table->string('project_type')->nullable(); // e.g. "Outreach", "Seminar"
            $table->text('rationale')->nullable();
            
            // 4. Logistics
            $table->string('venue')->nullable();
            $table->date('target_start_date')->nullable();
            $table->date('target_end_date')->nullable();
            $table->string('target_beneficiaries')->nullable(); // e.g. "50 Students"
            
            // 5. Financials
            $table->decimal('estimated_budget', 10, 2)->nullable();
            
            // 6. JSON Columns for Complex Data
            // Since this is a "Builder", it's easier to store lists as JSON first,
            // then normalize them into real tables (Objectives, SDGs) only AFTER approval.
            $table->json('objectives_data')->nullable(); // Stores array of objectives
            $table->json('budget_breakdown')->nullable(); // Stores line items
            $table->json('target_sdgs')->nullable(); // Stores selected SDG IDs
            
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