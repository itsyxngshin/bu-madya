<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add Parent ID for Nested Comments
        Schema::table('ibalong_post_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('ibalong_post_comments')->onDelete('cascade');
        });

        // 2. Create the Notifications Table
        Schema::create('ibalong_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Who receives it
            $table->string('type'); // e.g., 'mention', 'announcement', 'reply'
            $table->string('message');
            $table->string('link')->nullable(); // URL to redirect them to
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Assuming users are in users table, adjust to ibalong_users if necessary
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_notifications');
        
        Schema::table('ibalong_post_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};