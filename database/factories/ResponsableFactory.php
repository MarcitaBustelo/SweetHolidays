<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResponsableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'responsable_id' => $this->faker->unique()->optional()->numberBetween(1000, 9999),
        ];
    }
}
