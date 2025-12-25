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
        Schema::create('projects', function (Blueprint $table) {
            // Basic Info
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('project_category_id')->constrained('project_categories')->onDelete('cascade');       // Community Outreach, Environmental...
            $table->string('status');         // Upcoming, Ongoing, Completed
            $table->date('implementation_date')->nullable();
            
            // Specifics
            $table->string('location')->nullable();
            $table->string('beneficiaries')->nullable(); // e.g., "150 Families"
            $table->string('proponent')->nullable();     // e.g., "Committee on Education"
            
            $table->text('description')->nullable();
            $table->string('cover_img')->nullable();

            // JSON Fields (The Dynamic Parts)
            // Using JSON allows us to store lists directly
            $table->json('impact_stats')->nullable();  // [{"label": "Volunteers", "value": "50"}]
            $table->json('partners_list')->nullable(); // ["LGU Legazpi", "Red Cross"]
            // 1. Link to the Committee leading it
            $table->foreignId('committee_id')->nullable()->constrained()->onDelete('set null');

            // 2. Link to a specific Project Head (User) - Optional but useful
            $table->foreignId('project_head_id')->nullable()->constrained('users')->onDelete('set null');

            // 3. Fallback Text (e.g. "Office of the President" - might not be a committee)
            $table->string('proponent_text')->nullable();
            
            // Link to Academic Year (Critical for filtering)
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
