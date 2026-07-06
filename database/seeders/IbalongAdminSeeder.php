<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\IbalongUser;

class IbalongAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SUPER ADMIN ACCOUNT (Role ID: 1)
        IbalongUser::updateOrCreate(
            ['email' => 'dg@bumadya.org'], // The login email
            [
                'role_id' => 1, 
                'name' => 'Adornado B. Cabalbag Jr.',
                'slug' => Str::slug('Adornado B. Cabalbag Jr.') . '-' . strtolower(Str::random(5)),
                'password' => Hash::make('Launchpad2026!'), // Default password
                'designation' => 'Technology Head',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. STANDARD ADMIN ACCOUNT (Role ID: 2)
        IbalongUser::updateOrCreate(
            ['email' => 'admin@bumadya.org'], 
            [
                'role_id' => 2, 
                'name' => 'HOI Secretariat',
                'slug' => Str::slug('HOI Secretariat') . '-' . strtolower(Str::random(5)),
                'password' => Hash::make('HOISecretariat2026!'),
                'designation' => 'System Administrator',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}