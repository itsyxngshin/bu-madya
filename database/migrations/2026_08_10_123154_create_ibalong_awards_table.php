<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ibalong_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained('ibalong_hackathons')->cascadeOnDelete();

            // Link to the winning team (nullable because you might create the award before assigning a winner)
            $table->foreignId('team_id')->nullable()->constrained('ibalong_registrations')->nullOnDelete();

            $table->string('title'); // e.g., "Grand Champion" or "AWS Choice Award"
            $table->string('type')->default('special'); // 'ranking' (1st, 2nd, 3rd) or 'special' (Best UI, Sponsor Awards)
            $table->text('remarks')->nullable(); // Optional prize details or citations
            $table->boolean('is_published')->default(false); // Controls if teams can see it on their dashboard

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_awards');
    }
};
