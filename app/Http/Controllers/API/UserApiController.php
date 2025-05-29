<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Holiday;
use App\Models\Festive;
use DateTime;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\Department;
use App\Models\Delegation;




class UserApiController extends Controller
{

    //VER AUSENCIAS CALENDARIO
    public function holiday()
    {
        $user = Auth::user();
        $userDelegationId = $user->delegation_id;

        $holidays = Holiday::with(['holidayType', 'employee'])
            ->where('employee_id', $user->id)
            ->get();

        $festives = Festive::where(function ($query) use ($userDelegationId) {
            $query->where('national', true)
                ->orWhere('delegation_id', $userDelegationId);
        })->get();

        return response()->json([
            'user' => $user,
            'holidays' => $holidays,
            'festives' => $festives,
        ]);
    }

    //VER PERFIL
    public function show()
    {
        $user = Auth::user()->load('department', 'delegation'); // Carga relaciones

        $holidays = Holiday::where('employee_id', $user->id)
            ->whereYear('start_date', date('Y'))
            ->get();

        $vacationDaysUsed = 0;
        $absenceDaysUsed = 0;
        $totalDaysUsed = 0;

        foreach ($holidays as $holiday) {
            $days = $this->calculateDays($holiday->start_date, $holiday->end_date);

            if ($holiday->holiday_id == 1) {
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

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'employee_id' => $user->employee_id,
                    'department' => $user->department ? $user->department->name : null,
                    'delegation' => $user->delegation ? $user->delegation->name : null,
                    'total_days' => $totalDays,
                    'remaining_days' => $remainingDays,
                ],
                'vacation_days_used' => $vacationDaysUsed,
                'absence_days_used' => $absenceDaysUsed,
                'total_days_used' => $totalDaysUsed,
                'upcoming_holidays' => $upcomingHolidays,
            ],
            'message' => 'User profile retrieved successfully.',
        ], 200);
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

    //MANDAR CORREO PARA PEDIR VACACIONES
    public function sendEmail(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'reason' => 'required|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Crear instancias de Carbon para las fechas
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));

        // Verificar si la fecha de fin es anterior a la de inicio
        if ($endDate->lt($startDate)) {
            return response()->json([
                'success' => false,
                'message' => 'The end date cannot be earlier than the start date.',
            ], 422);
        }

        // Verificar si el día siguiente al end_date es festivo
        $nextDay = $endDate->copy()->addDay();
        $isFestive = Festive::where('date', $nextDay->format('Y-m-d'))->exists();

        if ($isFestive) {
            return response()->json([
                'success' => false,
                'message' => 'If the next day is a festive day, the request must include it.',
            ], 422);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user'], 401);
        }

        // Obtener el responsable y verificar si tiene email
        $responsable = User::where('employee_id', $user->responsable)->first();

        if (!$responsable || !$responsable->email) {
            return response()->json(['success' => false, 'message' => 'Responsable´s email not found'], 404);
        }

        // Preparar los datos para el correo electrónico
        $data = [
            'name' => $user->name,
            'reason' => $request->input('reason'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'responsable_email' => $responsable->email,
        ];

        try {
            // Enviar el correo electrónico
            Mail::send('email', $data, function ($message) use ($data) {
                $message->to($data['responsable_email'])
                    ->subject('Solicitud de Ausencia');
            });

            return response()->json(['success' => true, 'message' => 'Email sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops! Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}
