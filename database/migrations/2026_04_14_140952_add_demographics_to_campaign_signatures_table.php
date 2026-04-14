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
        Schema::table('campaign_signatures', function (Blueprint $table) {
            // 1. Modify the existing user_id to allow null values for guests
            $table->foreignId('user_id')->nullable()->change();

            // 2. Add Guest Identity Fields
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');

            // 3. Add Demographic Fields
            $table->string('affiliation')->default('student')->after('guest_email');
            $table->string('program')->nullable()->after('college_id');
            $table->string('year_level')->nullable()->after('program');
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete()->after('affiliation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_signatures', function (Blueprint $table) {
            // Drop all the columns we just added
            $table->dropColumn([
                'guest_name',
                'guest_email',
                'affiliation',
                'college_id',
                'program',
                'year_level'
            ]);

            // Revert user_id to be required (Warning: This will fail if you have guest records in the DB when rolling back!)
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};