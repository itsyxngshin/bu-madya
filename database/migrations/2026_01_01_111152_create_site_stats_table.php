<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_stats', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // e.g., 'visitor_count'
        $table->bigInteger('value')->default(0);
        $table->timestamps();
    });
    
    // Initialize the counter immediately
    DB::table('site_stats')->insert([
        'key' => 'visitor_count',
        'value' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_stats');
    }
};
