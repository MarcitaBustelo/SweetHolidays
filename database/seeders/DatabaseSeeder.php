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
            'name' => 'Marcita',
            'email' => 'marcitabuxtelo@gmail.com',
            'NIF' => '49689099A',
            'password' => '12345678',
            'role' => 'responsable',
            'phone' => '634623172',
            // 'department_id' => 1,
            // 'delegation_id' => 1,
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-01-01',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Ana Prat',
            'email' => 'anaprat26@gmail.com',
            'NIF' => '49559109C',
            'password' => '12345678',
            'role' => 'responsable',
            'phone' => '601289603',
            // 'department_id' => 1,
            // 'delegation_id' => 2,
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-01-21',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Antonio Bonet',
            'email' => 'antoniobonetoteroo@gmail.com',
            'NIF' => '49628498T',
            'password' => '12345678',
            'role' => 'employee',
            'phone' => '647135029',
            // 'department_id' => 2,
            // 'delegation_id' => 1,
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-01-13',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'María del Mar Bustelo',
            'email' => 'bustelo.brmar21@cadiz.salesianos.edu',
            'NIF' => '46788089B',
            'password' => '12345678',
            'role' => 'responsable',
            'phone' => '123456789',
            // 'department_id' => 3,
            // 'delegation_id' => 3,
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-02-26',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Ana Nicoleta Garrido',
            'email' => 'garrido.anpra22@cadiz.salesianos.edu',
            'NIF' => '12345678F',
            'password' => '12345678',
            'role' => 'employee',
            'phone' => '601289603',
            // 'department_id' => 4,
            // 'delegation_id' => 1,
            // 'responsable_id' => '100001',
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-02-03',
        ]);

        \App\Models\Department::factory()->create([
            'name' => 'Boss',  
        ]);
        \App\Models\Department::factory()->create([
            'name' => 'Inventory',
        ]);
        \App\Models\Department::factory()->create([
            'name' => 'Head chef',  
        ]);
        \App\Models\Department::factory()->create([
            'name' => 'Cook',
        ]);

        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-CADIZ',  
        ]);
        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-SEVILLA',
        ]);
        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-BARCELONA',  
        ]);

        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-MADRID',
        ]);
    }
}
