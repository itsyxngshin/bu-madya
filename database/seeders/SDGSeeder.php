<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sdg;
use Illuminate\Support\Str;

class SdgSeeder extends Seeder
{
    public function run()
    {
        $sdgs = [
            ['number' => 1,  'name' => 'No Poverty', 'color_hex' => '#E5243B'],
            ['number' => 2,  'name' => 'Zero Hunger', 'color_hex' => '#DDA63A'],
            ['number' => 3,  'name' => 'Good Health and Well-being', 'color_hex' => '#4C9F38'],
            ['number' => 4,  'name' => 'Quality Education', 'color_hex' => '#C5192D'],
            ['number' => 5,  'name' => 'Gender Equality', 'color_hex' => '#FF3A21'],
            ['number' => 6,  'name' => 'Clean Water and Sanitation', 'color_hex' => '#26BDE2'],
            ['number' => 7,  'name' => 'Affordable and Clean Energy', 'color_hex' => '#FCC30B'],
            ['number' => 8,  'name' => 'Decent Work and Economic Growth', 'color_hex' => '#A21942'],
            ['number' => 9,  'name' => 'Industry, Innovation and Infrastructure', 'color_hex' => '#FD6925'],
            ['number' => 10, 'name' => 'Reduced Inequalities', 'color_hex' => '#DD1367'],
            ['number' => 11, 'name' => 'Sustainable Cities and Communities', 'color_hex' => '#FD9D24'],
            ['number' => 12, 'name' => 'Responsible Consumption and Production', 'color_hex' => '#BF8B2E'],
            ['number' => 13, 'name' => 'Climate Action', 'color_hex' => '#3F7E44'],
            ['number' => 14, 'name' => 'Life Below Water', 'color_hex' => '#0A97D9'],
            ['number' => 15, 'name' => 'Life on Land', 'color_hex' => '#56C02B'],
            ['number' => 16, 'name' => 'Peace, Justice and Strong Institutions', 'color_hex' => '#00689D'],
            ['number' => 17, 'name' => 'Partnerships for the Goals', 'color_hex' => '#19486A'],
        ];

        foreach ($sdgs as $sdg) {
            Sdg::firstOrCreate(
                ['number' => $sdg['number']], // Check by number to avoid duplicates
                [
                    'name' => $sdg['name'],
                    'slug' => Str::slug($sdg['name']),
                    'color_hex' => $sdg['color_hex'],
                    // 'icon_path' => 'sdgs/goal-' . $sdg['number'] . '.png', // Optional: If you add images later
                ]
            );
        }
    }
}