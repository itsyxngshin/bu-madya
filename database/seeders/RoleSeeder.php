<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; 

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'role_name' => 'administrator',
                'role_description' => 'Full system access. Can manage users, settings, and website structure.',
            ],
            [
                'role_name' => 'director',
                'role_description' => 'Leadership access. Can manage specific committees, approve proposals, and post news.',
            ],
            [
                'role_name' => 'member',
                'role_description' => 'Standard access. Can view protected content, join committees, and update own profile.',
            ],
            [
                'role_name' => 'alumni',
                'role_description' => 'Limited access. Can view protected content, and update own profile, but cannot create new projects',
            ],

            [
                'role_name' => 'regular',
                'role_description' => 'Limited access. Can view content, and update own profile, and interact with activities',
            ],
        ];

        foreach ($roles as $role) {
            // firstOrCreate prevents duplicates if you run the seeder twice
            Role::firstOrCreate(
                ['role_name' => $role['role_name']], // Check if this name exists
                ['role_description' => $role['role_description']] // If not, create with this description
            );
        } 
    }
}
