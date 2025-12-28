<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\College;
use App\Models\Role;
use App\Models\Profile;
use App\Models\Director;
use App\Models\DirectorAssignment;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MadyaOfficialSeeder extends Seeder
{
    public function run()
    {
        // 1. GET YEAR & ROLE
        // Assumes AcademicYearSeeder and RoleSeeder ran first
        $currentYear = AcademicYear::where('name', '2025-2026')->first();
        $directorRole = Role::where('role_name', 'director')->first();

        if (!$currentYear || !$directorRole) {
            $this->command->error("Error: Missing Academic Year or Role. Please run previous seeders.");
            return;
        }

        // 2. THE DATA (Now with Photos!)
        $officers = [
            // --- EXECUTIVE COMMITTEE ---
            [
                'position' => 'Director General',
                'name_raw' => 'Cabalbag, Adornado Jr B.',
                'course' => 'BS Information Technology',
                'year' => '4th Year',
                'college_code' => 'bu-cs',
                'photo' => 'images/CABALBAG.png'
            ],
            [
                'position' => 'Director for External Affairs',
                'name_raw' => 'Oarde, Shiela Jean E.',
                'course' => 'BS Social Work',
                'year' => '3rd Year',
                'college_code' => 'bu-cssp',
                'photo' => 'images/OARDE.png'
            ],
            [
                'position' => 'Secretary-General',
                'name_raw' => 'Gerani, Maureen May L.',
                'course' => 'BS Economics',
                'year' => '4th Year',
                'college_code' => 'bu-cbem',
                'photo' => 'images/GERANI.jpg'
            ],
            [
                'position' => 'Deputy Secretary-General',
                'name_raw' => 'Cadiz, Francheska Nicole M.',
                'course' => 'BS Elementary Education',
                'year' => '2nd Year',
                'college_code' => 'bu-ce',
                'photo' => 'images/CADIZ.jpg'
            ],
            [
                'position' => 'Director for Finance',
                'name_raw' => 'Briol, Nicole Kate G.',
                'course' => 'BS Accountancy',
                'year' => '2nd Year',
                'college_code' => 'bu-cbem',
                'photo' => 'images/BRIOL.jpeg'
            ],
            [
                'position' => 'Deputy Director for Finance',
                'name_raw' => 'Briz, Danica Shien Marie R.',
                'course' => 'BSBA Management',
                'year' => '2nd Year',
                'college_code' => 'bu-cbem',
                'photo' => 'images/BRIZ.png'
            ],
            [
                'position' => 'Director for Audit',
                'name_raw' => 'Soreda, Kimberly B.',
                'course' => 'BS Geodetic Engineering',
                'year' => '3rd Year',
                'college_code' => 'bu-ceng',
                'photo' => 'images/SOREDA.jpeg'
            ],
            
            // --- DIRECTORS ---
            [
                'position' => 'Director for Public Affairs',
                'name_raw' => 'Esparrago, Kyle Emil E.',
                'course' => 'BS Entrepreneurship',
                'year' => '3rd Year',
                'college_code' => 'bu-cbem',
                'photo' => 'images/ESPARRAGO.png'
            ],
            [
                'position' => 'Director for Multimedia and Creatives',
                'name_raw' => 'Lique, Xanthie Luis S.',
                'course' => 'BS Electrical Engineering',
                'year' => '1st Year',
                'college_code' => 'bu-ceng',
                'photo' => 'images/LIQUE.png'
            ],
            [
                'position' => 'Director for Multimedia and Creatives',
                'name_raw' => 'Nuez, Ma. Allessandra B.',
                'course' => 'BS Chemistry',
                'year' => '4th Year',
                'college_code' => 'bu-cs',
                'photo' => 'images/NUEZ.jpeg'
            ],
            [
                'position' => 'Director for Marketing and Logistics',
                'name_raw' => 'Rosare, Rowena M.',
                'course' => 'BSBA Operations Management',
                'year' => '1st Year',
                'college_code' => 'bu-cbem',
                'photo' => null // No photo provided in list
            ],
            [
                'position' => 'Director for Strategic Initiatives',
                'name_raw' => 'Mendones, Lance RJ D.',
                'course' => 'Bachelor in Physical Education',
                'year' => '3rd Year',
                'college_code' => 'bu-ipesr',
                'photo' => 'images/MENDONES.jpg'
            ],
            [
                'position' => 'Director for Digital Strategies',
                'name_raw' => 'Garduque, Dana Zusha A.',
                'course' => 'BS Biology',
                'year' => '2nd Year',
                'college_code' => 'bu-cs',
                'photo' => 'images/GARDUQUE.jpg'
            ],
            [
                'position' => 'Director for Science and Technology',
                'name_raw' => 'Buenconsejo, Vincent A.',
                'course' => 'BS Information Technology',
                'year' => '2nd Year',
                'college_code' => 'bu-cs',
                'photo' => 'images/BUENCONSEJO.png'
            ],
            [
                'position' => 'Director for Social Science',
                'name_raw' => 'Monacillo, Briella Dee B.',
                'course' => 'BS Social Work',
                'year' => '1st Year',
                'college_code' => 'bu-cssp',
                'photo' => null // No photo provided
            ],
            [
                'position' => 'Director for Culture and Heritage',
                'name_raw' => 'Jacob, Khryssdale S.',
                'course' => 'AB Philosophy',
                'year' => '2nd Year',
                'college_code' => 'bu-cssp',
                'photo' => 'images/JACOB.jpg'
            ],
            [
                'position' => 'Director for Operations',
                'name_raw' => 'Orbana, Alexa S.',
                'course' => 'AB Journalism',
                'year' => '4th Year',
                'college_code' => 'bu-cal',
                'photo' => 'images/ORBANA.jpeg'
            ],
            [
                'position' => 'Director for Technical',
                'name_raw' => 'Cotara, Julius Christian C.',
                'course' => 'BSBA Management',
                'year' => '3rd Year',
                'college_code' => 'bu-cbem',
                'photo' => 'images/COTARA.jpeg'
            ],

            // --- ENVOYS ---
            [
                'position' => 'BU Legazpi - West Envoy',
                'name_raw' => 'Sorsogon, Mel Liza B.',
                'course' => 'AB Journalism',
                'year' => '3rd Year',
                'college_code' => 'bu-cal',
                'photo' => 'images/SORSOGON.png'
            ],
            [
                'position' => 'BU Legazpi - East Envoy',
                'name_raw' => 'Gubia, Darwin Isiah L.',
                'course' => 'BS Chemical Engineering',
                'year' => '3rd Year',
                'college_code' => 'bu-ceng',
                'photo' => 'images/GUBIA.jpg'
            ],
            [
                'position' => 'BU Daraga Envoy',
                'name_raw' => 'Nieva, Christine Joy Ll.',
                'course' => 'BSBA Management',
                'year' => '4th Year',
                'college_code' => 'bu-cbem',
                'photo' => 'images/NIEVA.jpeg'
            ],
        ];

        // 3. EXECUTION
        foreach ($officers as $officer) {
            
            // A. FIND POSITION
            $directorPosition = Director::where('name', 'LIKE', $officer['position'] . '%')->first();

            if (!$directorPosition) continue;

            // B. PARSE NAME
            $parts = explode(',', $officer['name_raw']);
            $lastName = trim($parts[0]);
            $firstNameRaw = trim($parts[1] ?? 'Unknown');
            $firstName = preg_replace('/\s+[A-Z]\.?$/', '', $firstNameRaw);

            $email = Str::slug($firstName . '.' . $lastName) . '@bu-madya.org';
            $college = College::where('slug', $officer['college_code'])->first();

            // C. CREATE OR UPDATE USER (Updating Photo Logic Added)
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $firstName . ' ' . $lastName,
                    'username' => Str::slug($firstName . '.' . $lastName),
                    'password' => Hash::make('password'),
                    'role_id' => $directorRole->id,
                    'email_verified_at' => now(),
                    // Assign photo initially
                    'profile_photo_path' => $officer['photo'],
                ]
            );

            // Force update photo if user already existed but photo was added later
            if ($officer['photo'] && $user->profile_photo_path !== $officer['photo']) {
                $user->forceFill(['profile_photo_path' => $officer['photo']])->save();
            }

            // D. PROFILE
            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName, 
                    'last_name' => $lastName,
                    'college_id' => $college?->id,
                    'course' => $officer['course'],
                    'year_level' => $officer['year'],
                    'bio' => "Serving as {$directorPosition->name} for A.Y. {$currentYear->name}",
                ]
            );

            // E. ASSIGNMENT
            DirectorAssignment::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'director_id' => $directorPosition->id,
                    'academic_year_id' => $currentYear->id,
                ],
                ['title' => $officer['position']]
            );
        }
    }
}