<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Main Posts Table
        Schema::create('ibalong_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The author (Admin, Facilitator, or Team)
            $table->text('content');
            $table->boolean('is_announcement')->default(false); // True if posted by Admins
            $table->timestamps();
        });

        // 2. Multiple Images per Post
        Schema::create('ibalong_post_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('ibalong_posts')->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });

        // 3. Post Likes (Toggle)
        Schema::create('ibalong_post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('ibalong_posts')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // A user can only like a specific post once
            $table->unique(['post_id', 'user_id']);
        });

        // 4. Post Comments
        Schema::create('ibalong_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('ibalong_posts')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_post_comments');
        Schema::dropIfExists('ibalong_post_likes');
        Schema::dropIfExists('ibalong_post_images');
        Schema::dropIfExists('ibalong_posts');
    }
};
