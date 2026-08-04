<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Evaluation Table
        Schema::create('ibalong_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('theme_color')->default('#FF8623');
            $table->string('header_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable();

            // Certificate Automation Fields
            $table->string('certificate_template')->nullable();
            $table->decimal('cert_pos_x', 5, 2)->default(50);
            $table->decimal('cert_pos_y', 5, 2)->default(50);
            $table->string('cert_text_color')->default('#131011');
            $table->integer('cert_font_size')->default(80);
            $table->string('cert_font_family')->default('Montserrat');
            $table->string('cert_text_align')->default('center');
            $table->string('cert_delivery_mode')->default('automatic');
            $table->boolean('cert_use_custom_email')->default(false);
            $table->string('cert_email_subject')->nullable();
            $table->text('cert_email_body')->nullable();
            $table->unsignedBigInteger('cert_name_question_id')->nullable();
            $table->unsignedBigInteger('cert_email_question_id')->nullable();

            $table->timestamps();
        });

        // 2. Questions Table
        Schema::create('ibalong_evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('ibalong_evaluations')->cascadeOnDelete();
            $table->string('type'); // text, radio, checkbox, section, page_break, etc.
            $table->text('question_text');
            $table->text('description')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });

        // 3. User Responses (The Submission Header)
        Schema::create('ibalong_evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('ibalong_evaluations')->cascadeOnDelete();
            $table->foreignId('team_id')->nullable(); // Linked to cohort/team if applicable
            $table->foreignId('user_id')->nullable(); // Linked to specific user if authenticated
            $table->timestamps();
        });

        // 4. Individual Answers
        Schema::create('ibalong_evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('ibalong_evaluation_responses')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('ibalong_evaluation_questions')->cascadeOnDelete();
            $table->text('answer_value')->nullable(); // Stores string or JSON array
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_evaluation_answers');
        Schema::dropIfExists('ibalong_evaluation_responses');
        Schema::dropIfExists('ibalong_evaluation_questions');
        Schema::dropIfExists('ibalong_evaluations');
    }
};
