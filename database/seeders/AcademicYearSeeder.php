<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        AcademicYear::firstOrCreate(
            ['name' => '2025-2026'], 
            [
                'start_date' => '2025-08-01',
                'end_date'   => '2026-07-31',
                'is_active'  => true
            ]
        );
    }
}