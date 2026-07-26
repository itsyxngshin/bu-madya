<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The Resource Group (The "Bar" / Folder)
        Schema::create('ibalong_resource_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('available_at')->nullable(); // Scheduled Drop Time
            $table->boolean('is_visible')->default(true); // Manual Override
            $table->timestamps();
        });

        // The Individual Files attached to the Group
        Schema::create('ibalong_resource_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('ibalong_resource_groups')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibalong_resource_files');
        Schema::dropIfExists('ibalong_resource_groups');
    }
};