<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color', 10)->default('#4b5563'); // Hex color code
            $table->string('logo_path')->nullable(); // The opaque logo/branding
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_parties');
    }
};
