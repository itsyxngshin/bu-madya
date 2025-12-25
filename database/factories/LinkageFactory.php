<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LinkageType;
use App\Models\LinkageStatus;
use App\Models\AgreementLevel;

class LinkageFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->company();
        
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'acronym' => strtoupper(substr($name, 0, 3)),
            
            // Randomly assign IDs from your lookup tables
            // We use 'inRandomOrder()->first()->id' to grab existing seeded data
            'linkage_type_id' => LinkageType::inRandomOrder()->first()->id ?? 1,
            'linkage_status_id' => LinkageStatus::inRandomOrder()->first()->id ?? 1,
            'agreement_level_id' => AgreementLevel::inRandomOrder()->first()->id ?? 1,

            'established_at' => $this->faker->date(),
            'expires_at' => $this->faker->dateTimeBetween('now', '+3 years'),

            'description' => $this->faker->paragraph(),
            'logo_path' => null, // Or put a default path
            
            'contact_person' => $this->faker->name(),
            'email' => $this->faker->companyEmail(),
            'website' => $this->faker->url(),
            'address' => $this->faker->address(),
        ];
    }
}