<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Holiday;
use DateTime;
use Illuminate\Support\Facades\Mail;
use App\Models\Festive;
use Carbon\Carbon;
use App\Models\Department;
use App\Models\HolidayType;
use App\Models\Delegation;



class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    //LOGIN
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'responsable':
                return view('menu_responsable', compact('user'));
            case 'admin':
                return view('menu.admin', compact('user'));
            default:
                return redirect()->route('logout');
        }
    }

    //METODO RESPONSABLES
    //Ver calendario
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
        if (in_array($loggedInEmployeeId, $specialAccessEmployeeIds)) {
            $festives = Festive::all();
        } else {
            $festives = Festive::where(function ($query) use ($userDelegationId) {
                $query->where('national', true)
                    ->orWhere('delegation_id', $userDelegationId);
            })->get();
        }


        $holiday_types = HolidayType::all();
        $delegations = Delegation::all();
        $departments = Department::all();

        // Pasar $specialAccessEmployeeIds a la vista
        return view('User.respon_calendar', compact('users', 'delegations', 'departments', 'holiday_types', 'holidays', 'festives', 'specialAccessEmployeeIds'));
    }

    //Ver Usuario
    public function showUsers()
    {
        $user = Auth::user();
        $specialAccessEmployeeIds = ['10001', '10003'];
        if (in_array($user->employee_id, $specialAccessEmployeeIds)) {
            $employees = User::with(['delegation', 'department'])->get();

            $responsables = User::where('role', 'responsable')
                ->select('employee_id', 'name')
                ->get();

            $departments = Department::select('department_id', 'name')->get();
        } else {
            $employees = User::where(function ($query) use ($user) {
                $query->where('responsable', $user->employee_id)
                    ->orWhere('id', $user->id);
            })->with(['delegation', 'department'])->get();

            $responsables = collect();
            $departments = collect();
        }

        return view('User.users', compact('employees', 'responsables', 'departments'));
    }

    //Actualizar los días totales de vacaciones que tienen los usuarios
    public function updateDays(Request $request, $id)
    {
        $request->validate([
            'days_in_total' => 'required|integer|min:0',
        ]);

        // Buscar al empleado por ID
        $employee = User::findOrFail($id);

        // Calcular los días de vacaciones ya usados este año por ese empleado
        $vacationDaysUsed = Holiday::where('employee_id', $employee->id)
            ->whereYear('start_date', date('Y'))
            ->where('holiday_id', 1) // Solo vacaciones
            ->get()
            ->reduce(function ($carry, $holiday) {
                return $carry + $this->calculateDays($holiday->start_date, $holiday->end_date);
            }, 0);

        // Validar si los días ingresados son menores a los días ya usados
        if ($request->input('days_in_total') < $vacationDaysUsed) {
            return redirect()->back()->withErrors([
                'days_in_total' => "You cant set total vacation days to less than the days already used ({$vacationDaysUsed}).",
            ])->withInput();
        }

        // Actualizar el campo
        $employee->days_in_total = $request->input('days_in_total');
        $employee->save();

        return redirect()->back()->with('success', 'Total vacation days updated successfully.');
    }



    //Resetear los dias de vacaciones cada año
    // public function editDaysPerYear($id)
    // {
    //     $employee = User::find($id);

    //     if (!$employee) {
    //         return response()->json(['error' => 'Empleado no encontrado.'], 404);
    //     }

    //     $currentDate = now();

    //     if ($currentDate->isSameDay(new DateTime('first day of January'))) {
    //         $remainingDays = $employee->days;

    //         $employee->days_in_total = 30;
    //         $employee->days = $employee->days_in_total + $remainingDays;

    //         $employee->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Días de vacaciones regenerados correctamente.',
    //             'days_in_total' => $employee->days_in_total,
    //             'days' => $employee->days,
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'No es 1 de enero. Los días de vacaciones no se pueden regenerar.',
    //     ]);
    // }


    // VER PERFIL (para responsables para ver el suyo, para empleados API para el movil)
    public function show()
    {
        $user = Auth::user();
        $holidays = Holiday::where('employee_id', $user->id)
            ->whereYear('start_date', date('Y'))
            ->get();

        $vacationDaysUsed = 0;
        $absenceDaysUsed = 0;

        foreach ($holidays as $holiday) {
            $days = $this->calculateDays($holiday->start_date, $holiday->end_date);

            if ($holiday->holiday_id == 1) {
                $vacationDaysUsed += $days;
            } else {
                $absenceDaysUsed += $days;
            }
        }

        $totalDays = $user->days_in_total;
        $remainingDays = $totalDays - $vacationDaysUsed;
        $totalDaysUsed = $vacationDaysUsed + $absenceDaysUsed;

        $upcomingHolidays = Holiday::where('employee_id', $user->id)
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('User.profile', compact(
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

    //Mandar email para solicitar vacaciones
    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reason' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $today = Carbon::today();
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));


        if ($endDate->lt($startDate)) {
            return response()->json([
                'success' => false,
                'message' => 'The end date cannot be earlier than the start date.',
            ], 422);
        }

        // Validar que ninguna fecha sea anterior a hoy
        if ($startDate->lt($today) || $endDate->lt($today)) {
            return response()->json([
                'success' => false,
                'message' => 'Start date and end date must be today or in the future.',
            ], 422);
        }

        // Validación adicional: si el último día es viernes, incluir sábado y domingo
        if ($endDate->isFriday()) {
            return response()->json([
                'success' => false,
                'message' => 'If the last day is Friday, the request must include Saturday and Sunday.',
            ], 422);
        }

        // Si el día siguiente es festivo
        $nextDay = $endDate->copy()->addDay();
        $isFestive = Festive::where('date', $nextDay->format('Y-m-d'))->exists();

        if ($isFestive) {
            return response()->json([
                'success' => false,
                'message' => 'If the next day is a festive day, the request must include it.',
            ], 422);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user'], 401);
        }

        $responsable = User::where('employee_id', $user->responsable)->first();

        if (!$responsable || !$responsable->email) {
            return response()->json(['success' => false, 'message' => 'Responsible\'s email not found'], 404);
        }

        $data = [
            'name' => $request->input('name'),
            'reason' => $request->input('reason'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'responsable_email' => $responsable->email,
        ];

        try {
            Mail::send('email', $data, function ($message) use ($data) {
                $message->to($data['responsable_email'])
                    ->subject('Absence Request from');
            });

            return response()->json(['success' => true, 'message' => 'Email sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops! Something went wrong: ' . $e->getMessage()], 500);
        }
    }


    public function updateResponsable(Request $request, $id)
    {
        $request->validate([
            'responsable' => 'nullable|exists:users,employee_id',
        ]);

        $employee = User::findOrFail($id);
        $employee->responsable = $request->input('responsable');
        $employee->save();

        return redirect()->back()->with('success', 'Responsible updated successfully.');
    }
    public function updateDepartment(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
        ]);

        $employee = User::find($id);

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $employee->department_id = $request->department_id;
        $employee->save();

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    //Activar desactivar
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        if ($user) {
            $user->active = !$user->active;
            $user->save();

            $message = $user->active ? 'Employee activated successfully.' : 'Employee activated successfully.';
        } else {
            $message = 'Employee not found.';
        }

        return redirect()->back()->with('success', $message);
    }

}
