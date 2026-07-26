<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_posts', function (Blueprint $table) {
            $table->string('author_display')->nullable()->after('user_id');
        });

        Schema::table('ibalong_post_comments', function (Blueprint $table) {
            $table->string('author_display')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_posts', function (Blueprint $table) {
            $table->dropColumn('author_display');
        });

        Schema::table('ibalong_post_comments', function (Blueprint $table) {
            $table->dropColumn('author_display');
        });
    }
};
