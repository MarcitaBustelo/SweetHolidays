<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Employee;
use App\Models\HolidayType;


class HolidayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),  // Genera un Employee asociado
            'start_date' => $this->faker->date(),  // Fecha de inicio aleatoria
            'end_date' => $this->faker->date(),    // Fecha de fin aleatoria
            'holiday_id' => HolidayType::factory(), // Genera un HolidaysType asociado
        ];
    }
}
