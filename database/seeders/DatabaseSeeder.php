<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Ana Prat',
            'email' => 'anaprat26@gmail.com',
            'password' => '12345678',
            'actived' => 1,
            'email_confirmed' => 1,
            'role' => 'employee',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Marcita',
            'email' => 'marcitabuxtelo@gmail.com',
            'password' => '12345678',
            'actived' => 1,
            'email_confirmed' => 1,
            'role' => 'responsable',
        ]);
    }
}
