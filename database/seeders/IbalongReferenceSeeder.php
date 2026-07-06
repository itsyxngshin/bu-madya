<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IbalongReferenceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles
        $roles = ['super admin', 'admin', 'teams', 'facilitators', 'judges'];
        foreach ($roles as $role) {
            DB::table('ibalong_roles')->insert(['name' => $role, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 2. Seed Skills
        $skills = [
            'Software Development', 'AI/ML', 'UI/UX', 'Graphic Design', 
            'Business Development', 'Marketing', 'Business', 'Entrepreneurship', 
            'Engineering', 'Tourism', 'Creative Industries', 'Disaster Risk Reduction', 
            'Research', 'Education', 'Healthcare', 'Data Analytics', 
            'Public Policy', 'Community Development', 'Communications'
        ];
        foreach ($skills as $skill) {
            DB::table('ibalong_skills')->insert(['name' => $skill, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 3. Seed Experiences
        $experiences = [
            'Hackathon', 'Startup Competition', 'Innovation Challenge', 
            'Incubation Program', 'Entrepreneurship Training', 'Research Competition', 
            'Design Thinking Competition', 'Not Yet'
        ];
        foreach ($experiences as $experience) {
            DB::table('ibalong_experiences')->insert(['name' => $experience, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 4. Seed Community Areas
        $areas = [
            'Tourism', 'Creative Economy', 'Entrepreneurships and MSMEs', 
            'Disaster Resilience', 'Environment', 'Agriculture', 'Education', 
            'Healthcare', 'Digital Government', 'Smart Cities', 'Artificial Intelligence', 
            'Youth Development', 'Mobility and Transportation', 'Community Development'
        ];
        foreach ($areas as $area) {
            DB::table('ibalong_community_areas')->insert(['name' => $area, 'created_at' => now(), 'updated_at' => now()]);
        }

        // 5. Seed Online Activities
        $activities = [
            'Online Orientation', 'Voices of Bicol', 
            'Human-Centered Design Workshop', 'Concept Proposal Orientation'
        ];
        foreach ($activities as $activity) {
            DB::table('ibalong_online_activities')->insert(['name' => $activity, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}