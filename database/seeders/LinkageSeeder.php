<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Linkage;
use App\Models\LinkageActivity;
use App\Models\Project;
use App\Models\Sdg;
class LinkageSeeder extends Seeder
{
    public function run()
    {
        // 2. Create 10 Dummy Partners
        $linkages = Linkage::factory()->count(10)->create();

        // 3. For each partner, add extra data...
        foreach ($linkages as $linkage) {
            
            // A. Create 3-5 Activities history
            LinkageActivity::factory()->count(rand(3, 5))->create([
                'linkage_id' => $linkage->id
            ]);

            // B. Attach Random SDGs (e.g., Goal 4 and 17)
            // (Assuming you have populated the SDGs table already)
            $sdgs = Sdg::inRandomOrder()->limit(rand(1, 4))->pluck('id');
            $linkage->sdgs()->attach($sdgs);

            // C. Attach Random Projects (e.g., they partnered on Project ID 1)
            // (Assuming you have populated the Projects table)
            if (Project::count() > 0) {
                $projects = Project::inRandomOrder()->limit(rand(1, 2))->pluck('id');
                // Attach with a role pivot
                foreach($projects as $pid) {
                    $linkage->projects()->attach($pid, ['role' => 'Co-Organizer']);
                }
            }
        }
    }
}