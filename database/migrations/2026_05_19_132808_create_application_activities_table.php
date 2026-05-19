<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_application_id')
                  ->constrained('accreditation_applications')
                  ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('target_month')->nullable(); // e.g., 'August 2026'

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_activities');
    }
};
