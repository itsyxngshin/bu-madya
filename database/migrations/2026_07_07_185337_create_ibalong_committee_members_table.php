<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ibalong_committee_members', function (Blueprint $table) {
            $table->id();
            $table->string('committee_name'); // Customizable (e.g., "Secretariat", "Logistics")
            $table->string('name');
            $table->string('affiliation')->nullable(); // e.g., "Bicol University"
            $table->string('designation')->nullable(); // e.g., "Director-General"
            $table->enum('role', ['Head', 'Member'])->default('Member');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_committee_members');
    }
};
