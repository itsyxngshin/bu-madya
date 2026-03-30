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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('certificate_template')->nullable();
            $table->decimal('cert_pos_x', 5, 2)->default(50.00); // Default 50% (Center)
            $table->decimal('cert_pos_y', 5, 2)->default(50.00); // Default 50% (Middle)
            $table->string('cert_text_color')->default('#1f2937');
            $table->integer('cert_font_size')->default(80);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            //
        });
    }
};
