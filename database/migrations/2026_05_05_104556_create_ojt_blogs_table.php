<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ojt_blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Differentiates between 'daily_report' and 'weekly_summary'
            $table->string('type')->default('daily_report');

            // The date this blog represents (links back to your time log)
            $table->date('report_date');

            $table->string('title');

            // Long text for your Alpine/Livewire rich text editor
            $table->longText('content');

            // Optional: If you want to attach screenshots of your work
            $table->string('attachment_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ojt_blogs');
    }
};
