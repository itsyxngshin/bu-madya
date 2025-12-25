<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colleges = [
            // Main Campus / Legazpi
            ['name' => 'College of Science', 'slug' => 'bu-cs'],
            ['name' => 'College of Engineering', 'slug' => 'bu-ceng'],
            ['name' => 'College of Arts and Letters', 'slug' => 'bu-cal'],
            ['name' => 'College of Social Sciences and Philosophy', 'slug' => 'bu-cssp'],
            ['name' => 'College of Business, Economics and Management', 'slug' => 'bu-cbem'],
            ['name' => 'College of Education', 'slug' => 'bu-ce'],
            ['name' => 'College of Nursing', 'slug' => 'bu-cn'],
            ['name' => 'Institute of Architecture', 'slug' => 'bu-idea'],
            ['name' => 'Institute of Physical Education, Sports and Recreation', 'slug' => 'bu-ipesr'],
            ['name' => 'College of Medicine', 'slug' => 'bu-cm'],
            
            // Daraga Campus
            ['name' => 'College of Social Sciences and Philosophy', 'slug' => 'bucssp'], // Often shared logic
            
            // East Campus
            ['name' => 'College of Industrial Technology', 'slug' => 'bucit'],
            
            // Polangui
            ['name' => 'Polangui Campus', 'slug' => 'bu-polangui'],
            
            // Tabaco
            ['name' => 'Tabaco Campus', 'slug' => 'bu-tabaco'],
            
            // Guinobatan
            ['name' => 'Guinobatan Campus', 'slug' => 'bu-guinobatan'],
        ];

        foreach ($colleges as $college) {
            // "insertOrIgnore" is great here: if the college exists, it skips it.
            // This prevents duplicate errors if you run the seeder twice.
            DB::table('colleges')->insertOrIgnore([
                'name' => $college['name'],
                'slug' => $college['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
