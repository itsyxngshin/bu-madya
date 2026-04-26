<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\College;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\Candidate;
use App\Models\CandidatePlatform;
use App\Models\CandidateCredential;
use App\Models\VoterLog;
use App\Models\Vote;
use Faker\Factory as Faker;

class ElectionSystemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();

        // 1. Fetch Existing Data (Fails gracefully if you haven't set them up yet)
        $admin = User::first(); // Grabs your existing first user (usually the super admin)
        
        if (!$admin) {
            $this->command->error('No users found in the database. Please create your admin account first.');
            return;
        }

        $collegeIds = College::pluck('id')->toArray();
        
        if (empty($collegeIds)) {
            $this->command->error('No colleges found in the database. Please populate the colleges table first.');
            return;
        }

        $this->command->info("Building Election using Admin ID: {$admin->id} and " . count($collegeIds) . " existing colleges...");

        // 2. Create the Main Active Election
        // Voting Start is yesterday, Voting End is tomorrow so it shows up as "LIVE!"
        $election = Election::create([
            'user_id' => $admin->id,
            'title' => '2026 BU MADYA General Elections',
            'slug' => '2026-bu-madya-general-elections',
            'description' => 'The official general elections for the 2026-2027 Executive Board.',
            'type' => 'general',
            'status' => 'active',
            'allow_guest_voting' => true,
            'application_start' => $now->copy()->subDays(14),
            'application_end' => $now->copy()->subDays(7),
            'voting_start' => $now->copy()->subDays(1),
            'voting_end' => $now->copy()->addDays(2),
            'results_release' => $now->copy()->addDays(3),
        ]);

        // 3. Create Positions
        $positionsData = [
            ['title' => 'President', 'max_winners' => 1],
            ['title' => 'Vice President', 'max_winners' => 1],
            ['title' => 'Secretary-General', 'max_winners' => 1],
            ['title' => 'Board of Directors', 'max_winners' => 3],
        ];

        $positions = [];
        foreach ($positionsData as $index => $pos) {
            $positions[] = ElectionPosition::create([
                'election_id' => $election->id,
                'title' => $pos['title'],
                'max_winners' => $pos['max_winners'],
                'order' => $index,
            ]);
        }

        // 4. Create Candidates for each position
        $candidates = [];
        foreach ($positions as $position) {
            // 3 candidates per position (except Board of Directors, let's make 5)
            $candidateCount = $position->title === 'Board of Directors' ? 5 : 3;

            for ($i = 0; $i < $candidateCount; $i++) {
                
                // Create a dummy user for the candidate to attach to
                $candidateUser = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'),
                ]);

                // Create the Candidate Record
                $candidate = Candidate::create([
                    'user_id' => $candidateUser->id,
                    'election_id' => $election->id,
                    'election_position_id' => $position->id,
                    'college_id' => $faker->randomElement($collegeIds),
                    'program' => 'BS ' . $faker->jobTitle,
                    'year_level' => $faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
                    'address' => $faker->city . ', ' . $faker->state,
                    'profile_photo_path' => null, // UI fallbacks handle null
                    'e_signature_path' => null,
                    // Mix of statuses to populate the vetting dashboard
                    'status' => $faker->randomElement(['approved', 'approved', 'approved', 'pending', 'rejected']),
                ]);

                if ($candidate->status === 'approved') {
                    $candidates[$position->id][] = $candidate;
                }

                // Add Dummy Platforms
                CandidatePlatform::create([
                    'candidate_id' => $candidate->id,
                    'title' => 'Student Welfare Initiative',
                    'description' => $faker->paragraph,
                ]);

                // Add Dummy Credentials
                CandidateCredential::create([
                    'candidate_id' => $candidate->id,
                    'type' => 'Experience',
                    'description' => 'Officer at ' . $faker->company,
                ]);
            }
        }

        // 5. Cast Dummy Votes! (Generating 50 random guest voters)
        $this->command->info('Simulating 50 live votes...');
        
        for ($v = 0; $v < 50; $v++) {
            
            // Log the Voter
            $voterLog = VoterLog::create([
                'election_id' => $election->id,
                'guest_name' => $faker->name,
                'guest_email' => $faker->unique()->safeEmail,
                'college_id' => $faker->randomElement($collegeIds),
                'program' => 'BS ' . $faker->jobTitle,
                'year_level' => $faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
                'voted_at' => $now->copy()->subMinutes(rand(1, 1440)), // Random time in the last 24 hours
            ]);

            // Randomly select candidates for this voter
            foreach ($positions as $position) {
                if (!isset($candidates[$position->id])) continue;

                // Pick random winners based on the max_winners allowed
                $chosenCandidates = $faker->randomElements(
                    $candidates[$position->id], 
                    rand(1, min(count($candidates[$position->id]), $position->max_winners))
                );

                foreach ($chosenCandidates as $chosen) {
                    Vote::create([
                        'election_id' => $election->id,
                        'election_position_id' => $position->id,
                        'candidate_id' => $chosen->id,
                    ]);
                }
            }
        }

        $this->command->info('✅ Election Simulation Complete! Dashboards are now populated.');
    }
}