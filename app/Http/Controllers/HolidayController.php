<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\HolidayType;
use App\Models\User;


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
            $employee = User::find($request->employee_id);

            if (!$employee) {
                return response()->json(['error' => 'This employee doesn´t exist'], 404);
            }

            if ($employee->id === Auth::id() && Auth::user()->responsable === null) {
                $employee = Auth::user();
            }

            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $interval = $startDate->diff($endDate);
            $daysRequested = $interval->days;

            // Solo cuenta si el tipo es "vacation"
            if ($request->holiday_id == 1 && $employee->days < $daysRequested) {
                return response()->json(['error' => 'The employee doesnt have that many days left'], 400);
            }

            // Crear la ausencia (ajustar fecha si es necesario)
            $adjustedStartDate = date('Y-m-d', strtotime($request->start_date . ' +1 day'));

            $holiday = Holiday::create([
                'employee_id' => $employee->id,
                'start_date' => $adjustedStartDate,
                'end_date' => $request->end_date,
                'holiday_id' => $request->holiday_id,
            ]);
            if ($request->holiday_id == 1) {
                $employee->days -= $daysRequested;
                $employee->save();
            }

            return response()->json([
                'success' => 'Absence added correctly.',
                'holiday_id' => $holiday->id,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
                'holiday_type' => $holiday->holiday_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something wrong happened while saving the absence'], 500);
        }
    }


    public function updateHoliday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holiday_id' => 'required|exists:holidays,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'holiday_type_id' => 'nullable|exists:holidays_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $holiday = Holiday::find($request->holiday_id);

            if (!$holiday) {
                return response()->json(['error' => 'The absence doesn’t exist'], 404);
            }

            $user = $holiday->employee;
            $days=$user->days;

            $originalStart = new \DateTime($holiday->start_date);
            $originalEnd = new \DateTime($holiday->end_date);
            $originalDays = $originalStart->diff($originalEnd)->days + 1;

            $newStart = new \DateTime($request->start_date);
            $newEnd = new \DateTime($request->end_date);
            $newDays = $newStart->diff($newEnd)->days + 1;

            $adjustedStartDate = $newStart->modify('+1 day')->format('Y-m-d');

            $newHolidayTypeId = $request->holiday_type_id ?? $holiday->holiday_type_id;

            if ($holiday->holiday_type_id === 1) {
                $difference = $newDays - $originalDays;
                if ($difference > 0) {
                    $days -= $difference;
                    $user->save();
                } elseif ($difference < 0) {
                    // Se han cogido menos días: SUMAR
                    $days += abs($difference);
                    $user->save();
                }
            }

            $holiday->update([
                'start_date' => $adjustedStartDate,
                'end_date' => $request->end_date,
                'days' => $days,
                'holiday_type_id' => $newHolidayTypeId,
            ]);

            return response()->json([
                'success' => 'Updated absence successfully.',
                'holiday' => $holiday
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something wrong happened while saving the absence'], 500);
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

            $employee = $holiday->employee;

            if (!$employee) {
                Log::warning('El empleado asociado no existe.', ['holiday_id' => $request->holiday_id]);
                return response()->json(['error' => 'El empleado asociado no existe.'], 404);
            }

            $startDate = new \DateTime($holiday->start_date);
            $endDate = new \DateTime($holiday->end_date);
            $interval = $startDate->diff($endDate);
            $daysToReturn = $interval->days + 1;

            $employee->days += $daysToReturn;
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

    public function editJustifyHoliday(Request $request)
    {
        $request->validate([
            'holiday_id' => 'required|exists:holidays,id',
            'comment' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        try {
            $holiday = Holiday::findOrFail($request->holiday_id);
            if ($request->has('comment')) {
                $holiday->comment = $request->comment;
            }
            if ($request->hasFile('file')) {
                $employeeId = $holiday->employee_id;
                $date = now()->format('Ymd');
                $nextFileIndex = Holiday::where('employee_id', $employeeId)
                    ->where('file', 'LIKE', "justified/{$employeeId}{$date}%")
                    ->count() + 1;

                $fileName = "proof_{$employeeId}_{$date}_{$nextFileIndex}." . $request->file('file')->getClientOriginalExtension();
                $filePath = $request->file('file')->storeAs('images', $fileName, 'public');

                $holiday->file = $filePath;
            }

            $holiday->save();

            return response()->json([
                'success' => true,
                'message' => 'The absence has been updated successfully.',
                'holiday' => $holiday,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la ausencia: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al actualizar la ausencia.'], 500);
        }
    }

    public function getHoliday($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);

            return response()->json([
                'success' => true,
                'holiday_id' => $holiday->id,
                'comment' => $holiday->comment,
                'file' => $holiday->file,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener los detalles de la ausencia: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al obtener los detalles de la ausencia.'], 500);
        }
    }

    public function updateType(Request $request)
    {
        $request->validate([
            'holiday_id' => 'required|exists:holidays,id',
            'absenceType' => 'required|exists:holidays_types,id',
        ]);

        $holiday = Holiday::findOrFail($request->holiday_id);
        $holiday->holiday_id = $request->absenceType;
        $holiday->save();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de ausencia actualizado correctamente.'
        ]);
    }

    public function getHolidaysByTypeAndDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $specialAccessEmployeeIds = ['10001', '10003'];

        $user = Auth::user();
        $authEmployeeId = $user->employee_id;

        $hasSpecialAccess = in_array($authEmployeeId, $specialAccessEmployeeIds);

        if ($hasSpecialAccess) {
            $holidays = Holiday::with('holidayType', 'employee')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_date', '<', $request->start_date)
                                ->where('end_date', '>', $request->end_date);
                        });
                })
                ->get();
        } else {
            $employeeIds = User::where('responsable', $authEmployeeId)->pluck('id')->toArray();
            if (empty($employeeIds)) {
                return response()->json([
                    'success' => true,
                    'absences' => [],
                    'message' => 'No se encontraron empleados bajo su responsabilidad.',
                ]);
            }
            $holidays = Holiday::with('holidayType', 'employee')
                ->whereIn('employee_id', $employeeIds)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_date', '<', $request->start_date)
                                ->where('end_date', '>', $request->end_date);
                        });
                })
                ->get();
        }

        $holidayTypes = HolidayType::pluck('color', 'type')->toArray();

        $absences = [];
        foreach ($holidays as $holiday) {
            $startDate = new \DateTime($holiday->start_date);
            $endDate = new \DateTime($holiday->end_date ?? $holiday->start_date);
            $dateInterval = new \DateInterval('P1D');
            $dateRange = new \DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day'));

            foreach ($dateRange as $date) {
                $formattedDate = $date->format('Y-m-d');
                $type = $holiday->holidayType->type;

                if (!isset($absences[$formattedDate])) {
                    $absences[$formattedDate] = [];
                }

                if (!isset($absences[$formattedDate][$type])) {
                    $absences[$formattedDate][$type] = [
                        'count' => 0,
                        'color' => $holidayTypes[$type] ?? '#6c757d',
                    ];
                }

                $absences[$formattedDate][$type]['count']++;
            }
        }

        return response()->json([
            'success' => true,
            'absences' => $absences,
        ]);
    }

    public function showHolidayManagementPage()
    {
        $holidayTypes = HolidayType::all();
        return view('holidays.index', compact('holidayTypes'));
    }
}