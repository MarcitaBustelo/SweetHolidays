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



class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
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

    public function showAll()
    {

        $users = User::all();
        return view('user.show', compact('users'));
    }

    //METODO RESPONSABLES

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

        return view('user.users', compact('employees', 'responsables', 'departments'));
    }

    // VER PERFIL (para responsables para ver el suyo, para empleados API para el movil)
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

            if ($holiday->holiday_type == 'Vacations') {
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

    //Mandar email para solicitar vacaciones
    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'reason' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));

        if ($endDate->isFriday()) {
            return response()->json([
                'success' => false,
                'message' => 'If the last day is friday, the request must include saturday and sunday.',
            ], 422);
        }

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
            return response()->json(['success' => false, 'message' => 'Responsable´s email not found'], 404);
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
                    ->subject('Solicitud de Ausencia');
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

        return redirect()->back()->with('success', 'Responsable updated successfully.');
    }
    public function updateDepartment(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $employee = User::find($id);

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $employee->department_id = $request->department_id;
        $employee->save();

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    // VER CALENDARIO RESPONSABLE
    // public function responCalendar(Request $request)
    // {
    //     $request->validate([
    //         'delegation_id' => 'nullable|exists:delegations,id',
    //         'department_id' => 'nullable|exists:departments,id',
    //     ]);

    //     $loggedInUserId = Auth::id();
    //     $loggedInEmployee = User::find($loggedInUserId);

    //     if (!$loggedInEmployee) {
    //         abort(403, 'Usuario no encontrado.');
    //     }

    //     $loggedInEmployeeId = $loggedInEmployee->employee_id;

    //     Definir IDs de empleados con acceso especial
    //     $specialAccessEmployeeIds = ['10332', '10342'];

    //     if (in_array($loggedInEmployeeId, $specialAccessEmployeeIds)) {
    //         $users = User::with(['delegation', 'department'])->get();
    //     } else {
    //         $users = User::where('responsable', $loggedInEmployeeId)
    //             ->with(['delegation', 'department'])
    //             ->get();

    //         if ($loggedInEmployee->responsable === null) {
    //             $users->push($loggedInEmployee);
    //         }
    //     }

    //     $employeeIds = $users->pluck('id');
    //     $holidays = Holiday::whereIn('employee_id', $employeeIds)
    //         ->with(['employee', 'holidayType'])
    //         ->get()
    //         ->map(function ($holiday) {
    //             return [
    //                 'id' => $holiday->id,
    //                 'holiday_type' => $holiday->holidayType->type ?? 'Sin tipo',
    //                 'employee' => [
    //                     'id' => $holiday->employee->id,
    //                     'name' => $holiday->employee->name,
    //                     'delegation' => $holiday->employee->delegation->name ?? 'Sin delegación',
    //                     'department' => $holiday->employee->department->name ?? 'Sin departamento',
    //                 ],
    //                 'start_date' => $holiday->start_date,
    //                 'end_date' => $holiday->end_date,
    //                 'color' => $holiday->employee->color ?? '#094080',
    //             ];
    //         });

    //     $holiday_types = HolidayType::all();
    //     $delegations = Delegation::all();
    //     $departments = Department::all();

    //     Pasar $specialAccessEmployeeIds a la vista
    //     return view('user.respon_calendar', compact('users', 'delegations', 'departments', 'holiday_types', 'holidays', 'specialAccessEmployeeIds'));
    // }

    //METODOS PARA EMPLEADOS QUE HAY QUE HACER API

    //VER CALENDARIO 
    // public function holiday()
    // {
    //     $user = Auth::user();
    //     $userDelegationId = $user->delegation_id;


    //     $holidays = Holiday::with(['holidayType', 'employee'])->where('employee_id', $user->id)->get();
    //     $festives = Festive::where(function ($query) use ($userDelegationId) {
    //         $query->where('national', true)
    //             ->orWhere('delegation_id', $userDelegationId);
    //     })->get();

    //     return view('user.calendar', compact('user', 'holidays', 'festives'));
    // }


    // EDITAR DIAS DE VACACIONES
    // public function updateDays(Request $request, $id)
    // {
    //     $request->validate([
    //         'days_in_total' => 'required|integer|min:0',
    //     ]);

    //     $employee = User::find($id);

    //     if (!$employee) {
    //         return redirect()->back()->with('error', 'Empleado no encontrado.');
    //     }

    //     $holidays = Holiday::where('employee_id', $employee->id)
    //         ->whereYear('start_date', date('Y'))
    //         ->get();

    //     $daysUsed = 0;
    //     foreach ($holidays as $holiday) {
    //         $startDate = new DateTime($holiday->start_date);
    //         $endDate = $holiday->end_date ? new DateTime($holiday->end_date) : null;

    //         if ($endDate) {
    //             $endDate->modify('+1 day');
    //         }

    //         $interval = $startDate->diff($endDate ?: $startDate);
    //         $daysUsed += $interval->days;
    //     }
    //     $employee->days_in_total = $request->days_in_total;
    //     $employee->days = $employee->days_in_total - $daysUsed;
    //     if ($employee->days < 0) {
    //         $employee->days = 0;
    //     }

    //     $employee->save();

    //     return redirect()->back()->with('success', 'Días totales y días restantes actualizados correctamente.');
    // }

    // EDITAR DIAS DE VACACIONES CUANDO CAMBIA EL AÑO
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


    // MANDAR EMAIL PARA SOLICITAR VACACIONES
    // public function sendEmail(Request $request, $id)
    // {
    //     Validar las entradas del formulario
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'reason' => 'required|string|max:1000',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //     ]);

    //     $user = User::find($id);

    //     if ($user) {
    //         Obtener el employee_id del responsable del usuario
    //         $responsableId = $user->responsable;

    //         if ($responsableId) {
    //             $responsable = Employee::where('employee_id', $responsableId)->first();

    //             if ($responsable && $responsable->professional_email) {
    //                 $data = [
    //                     'name' => $request->input('name'),
    //                     'reason' => $request->input('reason'),
    //                     'start_date' => $request->input('start_date'),
    //                     'end_date' => $request->input('end_date'),
    //                     'responsable_email' => $responsable->professional_email,
    //                 ];

    //                 Enviar el correo
    //                 Mail::send('email', $data, function ($message) use ($data) {
    //                     $message->to($data['responsable_email'])
    //                         ->subject('Solicitud de Ausencia');
    //                 });

    //                 return response()->json(['success' => true, 'message' => 'Correo enviado con éxito.']);
    //             }

    //             return response()->json(['success' => false, 'message' => 'Correo del responsable no encontrado.'], 404);
    //         }

    //         return response()->json(['success' => false, 'message' => 'Responsable no asignado al usuario.'], 404);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
    // }

}
