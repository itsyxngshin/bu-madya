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
        Schema::create('roundtable_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('headline');
            $table->text('content');
            $table->string('slug')->unique();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        Schema::create('roundtable_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('roundtable_topic_id')->constrained('roundtable_topics')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roundtable_topics');
        Schema::dropIfExists('roundtable_replies');
    }
};
