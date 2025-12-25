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
        Schema::create('linkages', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // e.g. "LGU Legazpi City"
            $table->string('slug')->unique();   // For URL: /linkages/lgu-legazpi
            $table->string('acronym')->nullable(); // e.g. "DOH"
            
            // Classification
            // Instead of string 'type', we link to ID
            $table->foreignId('linkage_type_id')
                ->nullable()
                ->constrained('linkage_types')
                ->nullOnDelete();

            // Instead of string 'status', we link to ID
            $table->foreignId('linkage_status_id')
                ->nullable()
                ->constrained('linkage_statuses')
                ->nullOnDelete();

            // Instead of string 'agreement_level', we link to ID
            $table->foreignId('agreement_level_id')
                ->nullable()
                ->constrained('agreement_levels')
                ->nullOnDelete();
            
            // Dates
            $table->date('established_at')->nullable(); // When did the partnership start?
            $table->date('expires_at')->nullable();     // For MOA expiration alerts
            
            // Branding & Info
            $table->string('logo_path')->nullable();
            $table->string('cover_img_path')->nullable();
            $table->text('description')->nullable();
            
            // Contact Details
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linkages');
    }
};
