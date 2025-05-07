<?php

namespace Database\Factories;

use App\Models\Festive;
use App\Models\Delegation; // Asegúrate de importar el modelo Delegation si lo tienes
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Festive>
 */
class FestiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'date' => $this->faker->date(),
            'national' => $this->faker->boolean(),
        ];
    }
}
