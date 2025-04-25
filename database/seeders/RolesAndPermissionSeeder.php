<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $responsableRole = Role::firstOrCreate(['name' => 'responsable']);

        Permission::firstOrCreate(['name' => 'see all employees']);
        Permission::firstOrCreate(['name' => 'put free days']);
        Permission::firstOrCreate(['name' => 'edit free days']);
        Permission::firstOrCreate(['name' => 'delete free days']);
        Permission::firstOrCreate(['name' => 'see free days']);
        Permission::firstOrCreate(['name' => 'ask for free days']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'view profile']);



        $adminRole->givePermissionTo(
            [
                'see free days',
                'see all employees',
                'put free days',
                'edit free days',
                'delete free days',
                'manage users'
            ]
        );

        $employeeRole->givePermissionTo(
            [
                'see free days',
                'ask for free days', 
                'view profile'
            ]
        );

        $responsableRole->givePermissionTo(
            [
                'see free days',
                'ask for free days',
                'see all employees',
                'put free days',
                'edit free days',
                'delete free days'
            ]
        );
    }
}
