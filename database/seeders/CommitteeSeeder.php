<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        $committees = [
            // --- EXECUTIVE COMMITTEE (1-10) ---
            ['order' => 1, 'name' => 'Internal Affairs Committee'],
            ['order' => 2, 'name' => 'External Affairs Committee'],
            ['order' => 3, 'name' => 'Secretariat Affairs Committee'],
            ['order' => 4, 'name' => 'Finance Committee'],
            ['order' => 5, 'name' => 'Audit Committee'],
            ['order' => 6, 'name' => 'Public Affairs Committee'],
            ['order' => 7, 'name' => 'Multimedia & Creatives Committee'],
            ['order' => 8, 'name' => 'Operations & Documentation Committee'],
            ['order' => 9, 'name' => 'Technical & Productions Committee'],
            ['order' => 10, 'name' => 'Marketing & Logistics Committee'],

            // --- DIRECTORS (11-30) ---
            ['order' => 11, 'name' => 'Committee on Strategic Initiatives & Advocacy'],
            ['order' => 12, 'name' => 'Committee on Science & Technology'],
            ['order' => 13, 'name' => 'Committee on Education'],
            ['order' => 14, 'name' => 'Committee on Culture & Heritage'],
            ['order' => 15, 'name' => 'Committee on Social Sciences'],
            ['order' => 16, 'name' => 'Committee on Digital Strategies and Communication'],
            ['order' => 17, 'name' => 'Special Projects Committee'],
        ];

        $now = Carbon::now();

        foreach ($committees as $round) {
            // Using DB::table bypasses the Model and the 'ActiveYearScope' error
            DB::table('committees')->updateOrInsert(
                ['name' => $round['name']], // Check this column
                [
                    'order' => $round['order'],
                    'description' => 'Standing and Advocacy Committee',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}