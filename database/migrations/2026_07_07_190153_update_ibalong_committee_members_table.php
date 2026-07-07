<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ibalong_committee_members', function (Blueprint $table) {
            $table->dropColumn('committee_name');
            $table->foreignId('committee_id')->after('id')->constrained('ibalong_committees')->onDelete('cascade');
            $table->string('photo_path')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('ibalong_committee_members', function (Blueprint $table) {
            $table->dropForeign(['committee_id']);
            $table->dropColumn('committee_id');
            $table->dropColumn('photo_path');
            $table->string('committee_name')->after('id');
        });
    }
};
