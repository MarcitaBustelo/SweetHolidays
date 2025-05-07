<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\Delegation;
use App\Models\Department;
use App\Models\Responsable;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory()->create()->company_id,
            'delegation_id' => Delegation::factory()->create()->delegation_id,
            'full_name' => $this->faker->name(),
            'NIF' => $this->faker->unique()->bothify('########?'),
            'employee_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'professional_email' => $this->faker->unique()->safeEmail(),
            'department_id' => Department::factory()->create()->department_id,
            'phone' => $this->faker->phoneNumber(),
            'start_date' => $this->faker->date(),
            'responsable_id' => Responsable::factory()->create()->responsable_id,
            'days' => $this->faker->optional()->numberBetween(0, 30),
        ];
    }
}
