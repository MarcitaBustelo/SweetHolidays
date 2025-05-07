<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;

class ArrivalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(), 
            'date' => $this->faker->date(), 
            'arrival_time' => $this->faker->time('H:i'),
            'departure_time' => $this->faker->time('H:i'), 
            'late' => $this->faker->boolean(), 
        ];
    }
}
