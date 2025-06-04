<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Department;
use App\Models\Delegation;
use App\Models\Responsable;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;


class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $existingUser = User::where('employee_id', $row['employee_id'])->first();

        $department = Department::where('name', $row['department'])->first();
        $delegation = Delegation::where('name', $row['delegation'])->first();

        if ($existingUser) {
            $updateData = [
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'start_date' => $this->parseDate($row['start_date']),
                'delegation_id' => $delegation ? $delegation->delegation_id : 99,
                'department_id' => $department ? $department->department_id : 99,
                'responsable' =>  $row['responsable'] ?? 1,
            ];

            $existingUser->update($updateData);
            return null;
        }

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'NIF' => $row['nif'],
            'password' => Hash::make($row['password']),
            'role' => $row['role'],
            'phone' => $row['phone'],
            'employee_id' => $row['employee_id'],
            'department_id' => $department ? $department->department_id : 99,
            'delegation_id' => $this->getDelegationIdByName($row['delegation']),
            'responsable' => $row['responsable'] ?? 1,
            'days' => $row['days'] ?? 30,
            'days_in_total' => $row['days_in_total'] ?? 30,
            'active' => $row['active'] ?? 1,
            'start_date' => $this->parseDate($row['start_date']),
        ]);
    }

    private function parseDate($value)
    {
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getDelegationIdByName($delegationName)
    {
        // Si tienes un modelo Delegation, implementa esta función
        // por ejemplo: return Delegation::where('name', $delegationName)->value('id') ?? 1;
        return 1; // Valor por defecto o implementar según tu estructura
    }
}
