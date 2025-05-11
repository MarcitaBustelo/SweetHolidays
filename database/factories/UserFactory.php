<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'NIF' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => $this->faker->randomElement(['responsable', 'employee']),
            'phone' => $this->faker->phoneNumber(),
            'days' => $this->faker->numberBetween(0, 10),
            'days_in_total' => $this->faker->numberBetween(10, 30),
            'active' => $this->faker->boolean(90),
            'start_date' => $this->faker->date(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
