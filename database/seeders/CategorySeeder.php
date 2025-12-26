<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCategory;
use App\Models\ProjectCategory;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // -----------------------------------------
        // 1. News Categories
        // -----------------------------------------
        $newsCategories = [
            [
                'name' => 'Advocacy',
                'color' => 'bg-red-500', // Tailwind class for badge background
            ],
            [
                'name' => 'Announcement',
                'color' => 'bg-blue-500',
            ],
            [
                'name' => 'Event',
                'color' => 'bg-yellow-500',
            ],
            [
                'name' => 'Press Release',
                'color' => 'bg-gray-600',
            ],
            [
                'name' => 'Statement',
                'color' => 'bg-emerald-600',
            ],
        ];

        foreach ($newsCategories as $cat) {
            NewsCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // -----------------------------------------
        // 2. Project Categories
        // -----------------------------------------
        $projectCategories = [
            [
                'name' => 'Community Outreach',
                'color' => 'bg-green-600',
                'icon' => 'hand-heart', // Optional: You can store icon names (e.g. for Heroicons)
            ],

            [
                'name' => 'Advocacy Campaign',
                'color' => 'bg-pink-500',
                'icon' => 'color-swatch',
            ],

            [
                'name' => 'Capacity Building',
                'color' => 'bg-purple-600',
                'icon' => 'academic-cap',
            ],

            [
                'name' => 'Internal Affairs',
                'color' => 'bg-purple-600',
                'icon' => 'academic-cap',
            ],

            [
                'name' => 'Environmental',
                'color' => 'bg-emerald-500',
                'icon' => 'globe',
            ],
            [
                'name' => 'Policy & Research',
                'color' => 'bg-indigo-600',
                'icon' => 'document-text',
            ],
            [
                'name' => 'Arts & Culture',
                'color' => 'bg-pink-500',
                'icon' => 'color-swatch',
            ],
        ];

        foreach ($projectCategories as $cat) {
            ProjectCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
