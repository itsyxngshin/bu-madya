<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <--- Import this
use Carbon\Carbon;

class DirectorsSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            // --- EXECUTIVE COMMITTEE (1-10) ---
            ['order' => 1, 'name' => 'Director General'],
            ['order' => 2, 'name' => 'Director for Internal Affairs'],
            ['order' => 3, 'name' => 'Director for External Affairs'],
            ['order' => 4, 'name' => 'Secretary-General'],
            ['order' => 5, 'name' => 'Deputy Secretary-General'],
            ['order' => 6, 'name' => 'Director for Finance'],
            ['order' => 7, 'name' => 'Deputy Director for Finance'],
            ['order' => 8, 'name' => 'Director for Audit'],
            

            // --- DIRECTORS (11-30) ---
            ['order' => 11, 'name' => 'Director for Public Affairs'],
            ['order' => 12, 'name' => 'Director for Multimedia and Creatives'],
            ['order' => 13, 'name' => 'Director for Marketing and Logistics'],
            ['order' => 14, 'name' => 'Director for Strategic Initiatives'],
            ['order' => 15, 'name' => 'Director for Digital Strategies'],
            ['order' => 16, 'name' => 'Director for Science and Technology'],
            ['order' => 17, 'name' => 'Director for Social Science'],
            ['order' => 18, 'name' => 'Director for Culture and Heritage'],
            ['order' => 19, 'name' => 'Director for Operations'],
            ['order' => 20, 'name' => 'Director for Technical'],
            
            // --- ENVOYS (31+) ---
            ['order' => 31, 'name' => 'BU Legazpi - West Envoy'],
            ['order' => 32, 'name' => 'BU Legazpi - East Envoy'],
            ['order' => 33, 'name' => 'BU Daraga Envoy'],
            ['order' => 34, 'name' => 'BU Tabaco Envoy'],
            ['order' => 35, 'name' => 'BU Guinobatan Envoy'],
            ['order' => 36, 'name' => 'BU Polangui Envoy'],
            ['order' => 37, 'name' => 'BU Gubat Envoy'],
        ];

        $now = Carbon::now();

        foreach ($positions as $pos) {
            // Using DB::table bypasses the Model and the 'ActiveYearScope' error
            DB::table('directors')->updateOrInsert(
                ['name' => $pos['name']], // Check this column
                [
                    'order' => $pos['order'],
                    'description' => 'Official Executive Position',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}