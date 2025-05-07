<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'department_id' => $this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}
