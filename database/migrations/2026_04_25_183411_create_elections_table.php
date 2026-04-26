<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // The Org/Admin owner
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_photo_path')->nullable();
            $table->string('type')->default('general'); // general, special, runoff
            $table->string('status')->default('draft'); // draft, active, completed
            
            $table->dateTime('application_start')->nullable();
            $table->dateTime('application_end')->nullable();
            $table->dateTime('voting_start')->nullable();
            $table->dateTime('voting_end')->nullable();
            $table->dateTime('results_release')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};