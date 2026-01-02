<?php
 
namespace Database\Seeders; 

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransparencyCategory;
use Illuminate\Support\Str;

class TransparencyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Financial Reports',
                'color' => 'green', // Optional: for UI styling
            ],
            [
                'name' => 'Resolutions',
                'color' => 'blue',
            ],
            [
                'name' => 'Meeting Minutes',
                'color' => 'gray',
            ],
            [
                'name' => 'Project Updates',
                'color' => 'yellow',
            ],
            [
                'name' => 'Constitutional Amendments',
                'color' => 'red',
            ],
        ];

        foreach ($categories as $category) {
            TransparencyCategory::firstOrCreate(
                ['name' => $category['name']], // Check if 'name' exists
                [                              // If not, create with these details
                    'slug' => Str::slug($category['name']),
                    'color' => $category['color'] ?? 'gray',
                ]
            );
        }

    }
}
