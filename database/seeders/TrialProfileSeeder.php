<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <--- Import DB Facade
use App\Models\User;
use App\Models\Engagement; 
use App\Models\Portfolio;
use Carbon\Carbon;

class TrialProfileSeeder extends Seeder
{
    public function run()
    {
        // 1. Find Director General
        $user = User::where('email', 'like', 'adornado%')->with('profile')->first();

        if (!$user || !$user->profile) {
            $this->command->error("User or Profile not found. Run MadyaOfficialSeeder first.");
            return;
        }

        // 2. Add Engagements
        Engagement::create([
            'user_id' => $user->id,
            'title' => 'Speaker: Youth Leadership Summit 2025',
            'description' => 'Discussed the role of technology in modern governance.',
        ]);
        Engagement::create([
            'user_id' => $user->id,
            'title' => 'Organizer: Bicol Tech Expo',
            'description' => 'Led the logistics team for the regional tech showcase.',
        ]);

        // 3. Add Portfolios (The Data)
        $p1 = Portfolio::create([
            'designation' => 'President',
            'place' => 'BU College of Science Student Council',
            'duration' => '2023-2024',
            'status' => 'Former',
            'description' => 'Spearheaded the "Science for All" initiative.'
        ]);

        $p2 = Portfolio::create([
            'designation' => 'Lead Developer',
            'place' => 'BU MADYA Digital Team',
            'duration' => '2024-Present',
            'status' => 'Active',
            'description' => 'Developing the official organization website.'
        ]);

        // 4. Attach to Profile via PIVOT TABLE (Explicitly)
        // This solves your concern: we strictly reference 'portfolio_sets' here.
        $timestamp = Carbon::now();
        
        DB::table('portfolio_sets')->insertOrIgnore([
            [
                'profile_id' => $user->profile->id,
                'portfolio_id' => $p1->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'profile_id' => $user->profile->id,
                'portfolio_id' => $p2->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ]);
        
        $this->command->info("Trial data added for {$user->name}");
    }
}