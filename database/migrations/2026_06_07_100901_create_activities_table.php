<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The Org who posted
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('lead_organization')->nullable();

            // REMOVED: lead_focals & participants strings

            // ADDED: JSON column for multiple highlight photos
            $table->json('highlight_photos')->nullable();

            $table->string('nature_of_activity')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('sdg_id')->nullable()->constrained('sdgs')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('status')->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
