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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            
            // Core Content
            $table->string('title');
            $table->string('slug')->unique(); // For SEO URLs (e.g., /news/youth-summit-2025)
            $table->foreignId('news_category_id')->constrained('news_categories')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('author');         // Display name (e.g., "Secretariat")
            
            // Main Body
            $table->longText('content');      // longText allows for extensive articles
            
            // Visuals & Meta
            $table->string('cover_img')->nullable();
            $table->string('tags')->nullable(); // Comma-separated tags (e.g., "Youth, Leadership")
            
            // Publishing Control
            $table->date('published_at')->nullable();
            $table->boolean('is_featured')->default(false); // To pin to the top of the feed
            
            // Optional: Link to a system user
            $table->string('author_display_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
