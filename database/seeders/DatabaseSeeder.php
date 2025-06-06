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
            'department_id' => 1,
            'delegation_id' => 1,
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-01-01',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Antonio Bonet',
            'email' => 'antoniobonetoteroo@gmail.com',
            'NIF' => '49628498T',
            'password' => '12345678',
            'role' => 'employee',
            'phone' => '647135029',
            'department_id' => 2,
            'delegation_id' => 1,
            'responsable' => '10001',
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
            'department_id' => 5,
            'delegation_id' => 1,
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-02-26',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Pablo Pérez',
            'email' => 'pablo@gmail.com',
            'NIF' => '87654321W',
            'password' => '12345678',
            'role' => 'employee',
            'phone' => '601289603',
            'department_id' => 4,
            'delegation_id' => 1,
            'responsable' => '10001',
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-02-03',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Ana Prat',
            'email' => 'anaprat26@gmail.com',
            'NIF' => '49559109C',
            'password' => '12345678',
            'role' => 'responsable',
            'phone' => '601289603',
            'department_id' => 1,
            'delegation_id' => 2,
            'responsable' => '10001',
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-01-21',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Ana Nicoleta Garrido',
            'email' => 'garrido.anpra22@cadiz.salesianos.edu',
            'NIF' => '12345678F',
            'password' => '12345678',
            'role' => 'employee',
            'phone' => '601289603',
            'department_id' => 4,
            'delegation_id' => 1,
            'responsable' => '10004',
            'days' => 30,
            'days_in_total' => 30,
            'active' => 1,
            'start_date' => '2023-02-03',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Javi Bocanegra',
            'email' => 'javi@gmail.com',
            'NIF' => '12387645H',
            'password' => '12345678',
            'role' => 'employee',
            'phone' => '601289603',
            'department_id' => 2,
            'delegation_id' => 1,
            'responsable' => '10004',
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
         \App\Models\Department::factory()->create([
            'name' => 'Rhh',
        ]);
        \App\Models\Department::factory()->create([
            'name' => 'Waiter',
        ]);
        \App\Models\Department::factory()->create([ 
            'name' => 'Receptionist',
        ]);
        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-CADIZ',
        ]);
        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-BARCELONA',
        ]);

        \App\Models\Delegation::factory()->create([
            'name' => 'SWEET HOLIDAYS-MADRID',
        ]);

        \App\Models\Festive::factory()->create([
            'name' => 'New Year',
            'date' => '2025-01-01',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Epiphany',
            'date' => '2025-01-06',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Jueves Santo',
            'date' => '2025-04-17',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Viernes Santo',
            'date' => '2025-04-18',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Working Day',
            'date' => '2025-05-01',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Assumption of Mary',
            'date' => '2025-08-15',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'All Hallows Eve',
            'date' => '2025-10-31',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Spanish Constitution Day',
            'date' => '2025-12-06',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Inmaculada Concepción Day',
            'date' => '2025-12-08',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Christmas Day',
            'date' => '2025-12-25',
            'national' => 1,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Día de Andalucía',
            'date' => '2025-02-28',
            'delegation_id' => '1',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Traslado del Día de la Fiesta Nacional y de la Virgen del Pilar',
            'date' => '2025-10-13',
            'delegation_id' => '1',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Fiesta de la Com. de Madrid',
            'date' => '2025-05-02',
            'delegation_id' => '3',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'San Isidro',
            'date' => '2025-05-15',
            'delegation_id' => '3',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Santiago Apóstol',
            'date' => '2025-07-25',
            'delegation_id' => '3',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Nuestra Señora de la Almudena',
            'date' => '2025-11-09',
            'delegation_id' => '3',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Lunes de Pascua',
            'date' => '2025-04-21',
            'delegation_id' => '2',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'San Juan',
            'date' => '2025-06-24',
            'delegation_id' => '2',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'Diada Nacional de Cataluña',
            'date' => '2025-09-11',
            'delegation_id' => '2',
            'national' => 0,
        ]);
        \App\Models\Festive::factory()->create([
            'name' => 'San Esteban',
            'date' => '2025-12-26',
            'delegation_id' => '2',
            'national' => 0,
        ]);
    }
}
