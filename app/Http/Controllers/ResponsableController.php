<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Responsable;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Delegation;
use App\Models\Department;
use App\Models\HolidayType;
use App\Models\Holiday;
use App\Models\Festive;


class ResponsableController extends Controller
{
    /**
     * Muestra la lista de responsables.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $responsables = Responsable::all();
        return view('responsable.index', compact('responsables'));
    }

    public function responCalendar(Request $request)
    {
        $request->validate([
            'delegation_id' => 'nullable|exists:delegations,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $loggedInUserId = Auth::id();
        $loggedInEmployee = User::find($loggedInUserId);

        if (!$loggedInEmployee) {
            abort(403, 'Usuario no encontrado.');
        }

        $loggedInEmployeeId = $loggedInEmployee->employee_id;
        $specialAccessEmployeeIds = ['10001', '10003'];

       if (in_array($loggedInEmployeeId, $specialAccessEmployeeIds)) {
            $users = User::with(['delegation', 'department'])->get();
        } else {
            $users = User::where('responsable', $loggedInEmployeeId)
                ->with(['delegation', 'department'])
                ->get();

            if ($loggedInEmployee->responsable === null) {
                $users->push($loggedInEmployee);
            }
        }

        $employeeIds = $users->pluck('id');

        $holidays = Holiday::whereIn('employee_id', $employeeIds)
            ->with(['employee', 'holidayType'])
            ->get();

        if ($loggedInEmployee->responsable !== null) {
            $userHolidays = Holiday::where('employee_id', $loggedInUserId)
                ->with(['employee', 'holidayType'])
                ->get();
            $holidays = $holidays->merge($userHolidays);
        }

        $holidays = $holidays->map(function ($holiday) {
            return [
                'id' => $holiday->id,
                'holiday_type' => $holiday->holidayType->type ?? 'Sin tipo',
                'employee' => [
                    'id' => $holiday->employee->id,
                    'name' => $holiday->employee->name,
                    'delegation' => $holiday->employee->delegation->name ?? 'Sin delegación',
                    'department' => $holiday->employee->department->name ?? 'Sin departamento',
                ],
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
                'color' => $holiday->holidayType->color ?? '#094080', // Usar el color del tipo de ausencia
            ];
        });

        $userDelegationId = $loggedInEmployee->delegation_id;
        $festives = Festive::where(function ($query) use ($userDelegationId) {
            $query->where('national', true)
                ->orWhere('delegation_id', $userDelegationId);
        })->get();

        $holiday_types = HolidayType::all();
        $delegations = Delegation::all();
        $departments = Department::all();

        // Pasar $specialAccessEmployeeIds a la vista
        return view('user.respon_calendar', compact('users', 'delegations', 'departments', 'holiday_types', 'holidays', 'festives', 'specialAccessEmployeeIds'));
    }
}