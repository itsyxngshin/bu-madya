<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkageLookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Types
        $types = [
            ['name' => 'Government', 'slug' => 'govt', 'color' => 'bg-blue-100 text-blue-800'],
            ['name' => 'Non-Government (NGO)', 'slug' => 'ngo', 'color' => 'bg-green-100 text-green-800'],
            ['name' => 'Student Organizations', 'slug' => 'student-org', 'color' => 'bg-yellow-100 text-yellow-800'],
            ['name' => 'Private Sector', 'slug' => 'private', 'color' => 'bg-purple-100 text-purple-800'],
        ];
        foreach($types as $t) \App\Models\LinkageType::create($t);

        // 2. Seed Statuses
        $statuses = [
            ['name' => 'Active', 'slug' => 'active', 'color' => 'bg-emerald-100 text-emerald-800'],
            ['name' => 'Inactive', 'slug' => 'inactive', 'color' => 'bg-gray-100 text-gray-800'],
            ['name' => 'Ongoing', 'slug' => 'inactive', 'color' => 'bg-gray-100 text-gray-800'],
            ['name' => 'Pending Renewal', 'slug' => 'pending', 'color' => 'bg-orange-100 text-orange-800'],
        ];
        foreach($statuses as $s) \App\Models\LinkageStatus::create($s);

        // 3. Seed Agreement Levels
        $levels = [
            ['name' => 'Memorandum of Understanding (MOU)', 'slug' => 'mou'],
            ['name' => 'Memorandum of Agreement (MOA)', 'slug' => 'moa'],
            ['name' => 'Letter of Partnership', 'slug' => 'lop'],
            ['name' => 'Media Partnership', 'slug' => 'mp'],
            ['name' => 'Verbal / Informal', 'slug' => 'informal'],
        ];
        foreach($levels as $l) \App\Models\AgreementLevel::create($l);
    }
}
