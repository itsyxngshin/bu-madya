<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            // Link the organization profile to the official user account
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name')->unique();
            $table->enum('type', [
                'fraternity/sorority',
                'academic',
                'political',
                'socio-civic',
                'lifestyle',
                'environmental',
                'spiritual/religious',
                'others'
            ]);
            $table->string('type_specification')->nullable();
            $table->year('year_established');
            $table->string('email_address')->unique();
            $table->string('facebook_account')->nullable();

            $table->decimal('membership_fee', 8, 2)->default(0.00);
            $table->enum('collection_frequency', ['annual', 'semestral', 'none'])->default('none');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
