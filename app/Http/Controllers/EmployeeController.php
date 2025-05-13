<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Holiday;
use DateTime;
use Illuminate\Support\Facades\Mail;
use App\Models\Department;
use App\Models\Responsable;
use App\Models\Festive;
use Carbon\Carbon;


class EmployeeController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $specialAccessEmployeeIds = ['10332', '10342'];

        if (in_array($user->employee_id, $specialAccessEmployeeIds)) {
            $employees = User::with(['delegation', 'department'])->get();
            $responsables = Responsable::select('responsable_id', 'name')->get();
            $departments = Department::select('department_id', 'name')->get();
        } else {
            $employees = User::where(function ($query) use ($user) {
                $query->where('responsable', $user->employee_id)
                    ->orWhere('id', $user->id);
            })->with(['delegation', 'department'])->get();

            $responsables = collect();
            $departments = collect();
        }

        return view('user.users', compact('employees', 'responsables', 'departments'));
    }

    public function updateDays(Request $request, $id)
    {
        $request->validate([
            'days_in_total' => 'required|integer|min:0',
        ]);

        $employee = User::find($id);

        if (!$employee) {
            return redirect()->back()->with('error', 'Empleado no encontrado.');
        }

        $holidays = Holiday::where('employee_id', $employee->id)
            ->whereYear('start_date', date('Y'))
            ->get();

        $daysUsed = 0;
        foreach ($holidays as $holiday) {
            $startDate = new DateTime($holiday->start_date);
            $endDate = $holiday->end_date ? new DateTime($holiday->end_date) : null;

            if ($endDate) {
                $endDate->modify('+1 day');
            }

            $interval = $startDate->diff($endDate ?: $startDate);
            $daysUsed += $interval->days;
        }
        $employee->days_in_total = $request->days_in_total;
        $employee->days = $employee->days_in_total - $daysUsed;
        if ($employee->days < 0) {
            $employee->days = 0;
        }

        $employee->save();

        return redirect()->back()->with('success', 'Días totales y días restantes actualizados correctamente.');
    }

    public function editDaysPerYear($id)
    {
        $employee = User::find($id);

        if (!$employee) {
            return response()->json(['error' => 'Empleado no encontrado.'], 404);
        }

        $currentDate = now();

        if ($currentDate->isSameDay(new DateTime('first day of January'))) {
            $remainingDays = $employee->days;

            $employee->days_in_total = 30;
            $employee->days = $employee->days_in_total + $remainingDays;

            $employee->save();

            return response()->json([
                'success' => true,
                'message' => 'Días de vacaciones regenerados correctamente.',
                'days_in_total' => $employee->days_in_total,
                'days' => $employee->days,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No es 1 de enero. Los días de vacaciones no se pueden regenerar.',
        ]);
    }

    public function show()
    {
        $user = Auth::user();
        $holidays = Holiday::where('employee_id', $user->id)
            ->whereYear('start_date', date('Y'))
            ->get();

        $vacationDaysUsed = 0;
        $absenceDaysUsed = 0;
        $totalDaysUsed = 0;

        foreach ($holidays as $holiday) {
            $days = $this->calculateDays($holiday->start_date, $holiday->end_date);

            if ($holiday->holiday_type == 'Vacaciones') {
                $vacationDaysUsed += $days;
            } else {
                $absenceDaysUsed += $days;
            }

            $totalDaysUsed += $days;
        }
        $totalDays = $user->days_in_total;
        $remainingDays = $user->days;
        $upcomingHolidays = Holiday::where('employee_id', $user->id)
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('user.profile', compact(
            'user',
            'vacationDaysUsed',
            'absenceDaysUsed',
            'totalDaysUsed',
            'totalDays',
            'remainingDays',
            'upcomingHolidays'
        ));
    }

    private function calculateDays($startDate, $endDate = null)
    {
        if (!$endDate) {
            return 1;
        }

        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day');

        $interval = $start->diff($end);
        return $interval->days;
    }

    public function holiday()
    {
        $user = Auth::user();
        $userDelegationId = $user->delegation_id;


        $holidays = Holiday::with(['holidayType', 'employee'])->where('employee_id', $user->id)->get();
        $festives = Festive::where(function ($query) use ($userDelegationId) {
            $query->where('national', true)
                ->orWhere('delegation_id', $userDelegationId);
        })->get();

        return view('user.calendar', compact('user', 'holidays', 'festives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('employee');

        return redirect()->route('menu.employee')->with('success', 'Empleado creado con éxito.');
    }




}