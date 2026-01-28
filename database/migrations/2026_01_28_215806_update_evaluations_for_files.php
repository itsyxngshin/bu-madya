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
        // 1. Add Header Image to the main Evaluation
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('header_image')->nullable()->after('description');
        });

        // 2. Add Image support to Questions
        Schema::table('evaluation_questions', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('question_text');
            // We also need to ensure the 'type' column accepts 'file'
            // If 'type' is an enum, you might need to change it to string or update the enum
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down()
        {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->dropColumn('header_image');
            });
            
            Schema::table('evaluation_questions', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
};
