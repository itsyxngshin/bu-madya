<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_application_id')
                  ->constrained('accreditation_applications')
                  ->cascadeOnDelete();

            $table->string('position');
            $table->string('complete_name');
            $table->string('course_and_year');

            // Replaced string with Foreign Key
            $table->foreignId('college_id')->constrained()->cascadeOnDelete();

            $table->string('contact_number')->nullable();
            $table->string('email_address')->nullable();
            $table->text('home_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_officers');
    }
};
