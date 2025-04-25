<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class HolidayController extends Controller
{

    public function assignHoliday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'holiday_id' => 'required|exists:holidays_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $employee = \App\Models\User::find($request->employee_id);

            if (!$employee) {
                return response()->json(['error' => 'El empleado no existe.'], 404);
            }

            // Verificar si el usuario autenticado está intentando asignarse una ausencia
            if ($employee->id === Auth::id() && Auth::user()->responsable === null) {
                $employee = Auth::user(); // Usar el usuario autenticado
            }

            // Calcular los días solicitados
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $interval = $startDate->diff($endDate);
            $daysRequested = $interval->days;

            if ($employee->days < $daysRequested) {
                return response()->json(['error' => 'El empleado no tiene suficientes días disponibles.'], 400);
            }

            // Crear la ausencia
            $adjustedStartDate = date('Y-m-d', strtotime($request->start_date . ' +1 day'));

            $holiday = Holiday::create([
                'employee_id' => $employee->id,
                'start_date' => $adjustedStartDate,
                'end_date' => $request->end_date,
                'holiday_id' => $request->holiday_id,
            ]);

            // Restar los días del atributo `days`
            $employee->days -= $daysRequested;
            $employee->save();

            return response()->json([
                'success' => 'Ausencia registrada correctamente.',
                'holiday_id' => $holiday->id,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
                'holiday_type' => $holiday->holiday_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar la ausencia: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al guardar la ausencia.'], 500);
        }
    }

    public function updateHoliday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holiday_id' => 'required|exists:holidays,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $adjustedStartDate = date('Y-m-d', strtotime($request->start_date . ' +1 day'));
            $holiday = Holiday::find($request->holiday_id);

            if (!$holiday) {
                return response()->json(['error' => 'La ausencia no existe.'], 404);
            }

            $holiday->update([
                'start_date' => $adjustedStartDate,
                'end_date' => $request->end_date,
            ]);

            return response()->json([
                'success' => 'Ausencia actualizada correctamente.',
                'holiday' => $holiday
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la ausencia: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al actualizar la ausencia.'], 500);
        }
    }


    public function deleteHoliday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holiday_id' => 'required|exists:holidays,id',
        ]);

        if ($validator->fails()) {
            Log::info('Validación fallida', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            Log::info('Buscando la ausencia', ['holiday_id' => $request->holiday_id]);
            $holiday = Holiday::find($request->holiday_id);

            if (!$holiday) {
                Log::warning('La ausencia no existe o ya fue eliminada.', ['holiday_id' => $request->holiday_id]);
                return response()->json(['error' => 'La ausencia no existe o ya fue eliminada.'], 404);
            }

            // Obtener al empleado asociado
            $employee = $holiday->employee; // Asegúrate de que la relación esté definida en el modelo `Holiday`

            if (!$employee) {
                Log::warning('El empleado asociado no existe.', ['holiday_id' => $request->holiday_id]);
                return response()->json(['error' => 'El empleado asociado no existe.'], 404);
            }

            // Calcular los días de ausencia
            $startDate = new \DateTime($holiday->start_date);
            $endDate = new \DateTime($holiday->end_date);
            $interval = $startDate->diff($endDate);
            $daysToReturn = $interval->days + 1; // Incluir el último día

            // Devolver los días al empleado
            $employee->days += $daysToReturn;

            // Asegurarse de que no supere el máximo permitido (por ejemplo, 30 días)
            $maxDays = 30;
            if ($employee->days > $maxDays) {
                $employee->days = $maxDays;
            }

            $employee->save();

            // Eliminar la ausencia
            Log::info('Eliminando la ausencia', ['holiday' => $holiday]);
            $holiday->delete();

            Log::info('Ausencia eliminada correctamente.');
            return response()->json([
                'success' => 'Ausencia eliminada correctamente. Los días han sido devueltos.',
                'days_restored' => $daysToReturn,
                'employee_days' => $employee->days,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la ausencia: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al eliminar la ausencia.'], 500);
        }
    }
}
