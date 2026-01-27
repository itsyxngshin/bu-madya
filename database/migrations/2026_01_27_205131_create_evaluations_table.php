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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable(); 
            $table->text('description')->nullable();
            $table->string('type')->default('project'); // 'project', 'member', 'event'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. The Questions (Linked to Form)
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'text', 'textarea', 'radio', 'likert'
            $table->string('question_text');
            
            // KEY FEATURE: Store options (e.g., Likert scale labels) as JSON
            $table->json('options')->nullable(); 
            
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. The Response Header (Who submitted?)
        Schema::create('evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained(); // Nullable for anonymous
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // 4. The Specific Answers
        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_response_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluation_question_id')->constrained();
            $table->text('answer_value')->nullable(); // Stores the text or selected option
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('evaluation_questions');
        Schema::dropIfExists('evaluation_responses');
        Schema::dropIfExists('evaluation_answers');
    }
};
