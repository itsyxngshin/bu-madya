<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\College; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ---------------------------------------------------------
        // 1. CREATE SPECIFIC ACCOUNTS (Login using password: 'password')
        // ---------------------------------------------------------
        // A. The Super Admin (IT Head) - CS (College of Science)
        $this->createUser(
            roleId: 1, 
            email: 'admin@bu-madya.org',
            username: 'admin',
            firstName: 'System',
            lastName: 'Administrator',
            collegeId: 1, // Assumes ID 1 is College of Science
            course: 'BS Computer Science',
            roleName: 'IT Head'
        );

        // B. The Director-General - CSSP (College of Social Sciences)
        $this->createUser(
            roleId: 2, 
            email: 'director-general@bu-madya.org',
            username: 'acabalbag22',
            firstName: 'Adornado',
            lastName: 'Cabalbag Jr.',
            collegeId: 1, 
            course: 'BS Information Technology',
            roleName: 'Director-General'
        );

        // C. Finance Director - CBEM (College of Business)
        $this->createUser(
            roleId: 2, 
            email: 'finance@bu-madya.org',
            username: 'finance.head',
            firstName: 'Nicole Kate',
            lastName: 'Briol',
            collegeId: 5, // Assumes ID 5 is CBEM
            course: 'BS Accountancy',
            roleName: 'Finance Director'
        );

        // ---------------------------------------------------------
        // 2. CREATE RANDOM MEMBERS (Bulk Data)
        // ---------------------------------------------------------
        
        \App\Models\User::factory(10)->create()->each(function ($user) {
            $faker = \Faker\Factory::create();
            
            // Safer way to get a random college ID:
            // This picks a real ID from the DB, so it never fails.
            $randomCollege = College::inRandomOrder()->first(); 

            DB::table('profiles')->insert([
                'user_id' => $user->id,
                'first_name' => explode(' ', $user->name)[0], 
                'last_name' => explode(' ', $user->name)[1] ?? 'Member',
                'middle_name' => $faker->lastName,
                
                // Use the valid ID we found
                'college_id' => $randomCollege->id ?? 1, 
                
                'course' => $faker->randomElement(['BS IT', 'BS Nursing', 'BS Civil Eng', 'BA Comm', 'BS Bio']),
                'year_level' => $faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
                'bio' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Helper to create User + Profile
     */
    private function createUser($roleId, $email, $username, $firstName, $lastName, $collegeId, $course, $roleName)
    {
        $user = User::create([
            'name' => $firstName . ' ' . $lastName,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            // 'role_id' => $roleId, // Uncomment if using role_id on users table
        ]);

        DB::table('profiles')->insert([
            'user_id' => $user->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => 'A.',
            'student_id' => '2025-' . rand(10000, 99999),
            'college_id' => $collegeId,
            'course' => $course,
            'year_level' => '3rd Year',
            'bio' => "Official account for the $roleName.",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}