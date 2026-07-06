<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ibalong_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role'); // e.g., "Executive Festival Committee", "Innovation Support Partner"
            $table->string('logo_path');
            $table->enum('emphasis', ['small', 'medium'])->default('medium');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_partners');
    }
};
