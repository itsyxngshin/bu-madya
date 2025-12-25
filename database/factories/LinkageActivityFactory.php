<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LinkageActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4), // e.g. "Meeting with Mayor"
            'activity_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'description' => $this->faker->paragraph(),
            'photo_path' => null,
        ];
    }
}