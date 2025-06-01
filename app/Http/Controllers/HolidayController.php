<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\HolidayType;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

            if ($request->holiday_id == 1 && $employee->days < $daysRequested) {
                return response()->json(['error' => 'The employee doesnt have that many days left'], 400);
            }

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

            // Enviar correo al empleado
            try {
                $data = [
                    'name' => $employee->name,
                    'email' => $employee->email,
                ];

                Mail::send('email_approved', $data, function ($message) use ($data) {
                    $message->to($data['email'])
                        ->subject('Your Absence Has Been Approved');
                });
            } catch (\Exception $e) {
                Log::error('Email send error: ' . $e->getMessage());
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
            'end_date' => 'required|date|after_or_equal:start_date',
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

            if (!$user) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            if ($user->days === null) {
                $user->days = 0;
            }

            $originalStart = new \DateTime($holiday->start_date);
            $originalEnd = new \DateTime($holiday->end_date);
            $originalDays = $originalStart->diff($originalEnd)->days + 1;

            $newStart = new \DateTime($request->start_date);
            $newEnd = new \DateTime($request->end_date);
            $newDays = $newStart->diff($newEnd)->days;

            $adjustedStartDate = date('Y-m-d', strtotime($request->start_date . ' +1 day'));

            $newHolidayTypeId = $holiday->holiday_type_id ?? $holiday->holiday_id;
            if ($newHolidayTypeId === 1) {
                $difference = $newDays - $originalDays;

                if ($difference > 0) {
                    if ($user->days < $difference) {
                        return response()->json(['error' => 'The employee doesn’t have enough days left to extend the absence'], 400);
                    }
                    $user->days -= $difference;
                } elseif ($difference < 0) {
                    $user->days += abs($difference);
                }

                $user->save();
            }

            $holiday->update([
                'start_date' => $adjustedStartDate,
                'end_date' => $request->end_date,
                'holiday_type_id' => $newHolidayTypeId,
            ]);

            // Enviar correo al empleado
            try {
                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];

                Mail::send('updated_email', $data, function ($message) use ($data) {
                    $message->to($data['email'])
                        ->subject('Your Absence Has Been Approved');
                });
            } catch (\Exception $e) {
                Log::error('Email send error: ' . $e->getMessage());
            }

            return response()->json([
                'success' => 'Updated absence successfully.',
                'holiday' => $holiday
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something wrong happened while saving the absence',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteHoliday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holiday_id' => 'required|exists:holidays,id',
        ]);

        if ($validator->fails()) {
            Log::info('Failed validation', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            Log::info('Looking for absence', ['holiday_id' => $request->holiday_id]);
            $holiday = Holiday::find($request->holiday_id);

            if (!$holiday) {
                Log::warning('The absence doesnt exist or was already deleted', ['holiday_id' => $request->holiday_id]);
                return response()->json(['error' => 'absence doesnt exist or was already deleted'], 404);
            }

            $employee = $holiday->employee;

            if (!$employee) {
                Log::warning('The employee associated with doesnt exist.', ['holiday_id' => $request->holiday_id]);
                return response()->json(['error' => 'the employee associated with doesnt exist'], 404);
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

            Log::info('Deleting absence...', ['holiday' => $holiday]);
            $holiday->delete();

            // Enviar correo al empleado
            try {
                $data = [
                    'name' => $employee->name,
                    'email' => $employee->email,
                ];

                Mail::send('deleted_email', $data, function ($message) use ($data) {
                    $message->to($data['email'])
                        ->subject('Your Absence Has Been Deleted');
                });
            } catch (\Exception $e) {
                Log::error('Email send error (deletion): ' . $e->getMessage());
            }

            Log::info('Absence deleted successfully');
            return response()->json([
                'success' => 'Absence deleted successfully. The days were given back.',
                'days_restored' => $daysToReturn,
                'employee_days' => $employee->days,
            ]);
        } catch (\Exception $e) {
            Log::error('Error while deleting absence: ' . $e->getMessage());
            return response()->json(['error' => 'Something happened while deleting absence.'], 500);
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
                    ->where('file', 'LIKE', "images/proof_{$employeeId}_{$date}%")
                    ->count() + 1;

                $fileName = "proof_{$employeeId}_{$date}_{$nextFileIndex}." . $request->file('file')->getClientOriginalExtension();
                $destinationPath = public_path('storage/images');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true); // Crea el directorio si no existe
                }

                $request->file('file')->move($destinationPath, $fileName);

                $holiday->file = "images/$fileName";

            }

            $holiday->save();

            return response()->json([
                'success' => true,
                'message' => 'The absence has been updated successfully.',
                'holiday' => $holiday,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating absence: ' . $e->getMessage());
            return response()->json(['error' => 'Something happened while updating absence´s details.'], 500);
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
            Log::error('Something happened while obtaining absence´s details: ' . $e->getMessage());
            return response()->json(['error' => 'Something happened while obtaining absence´s details.'], 500);
        }
    }

    public function updateType(Request $request)
    {
        $request->validate([
            'holiday_id' => 'required|exists:holidays,id',
            'absenceType' => 'required|exists:holidays_types,id',
        ]);

        $holiday = Holiday::findOrFail($request->holiday_id);

        // Si el nuevo tipo es VACATION (ID=1)
        if ((int) $request->absenceType === 1) {
            $user = $holiday->employee; // Ajusta si tu relación es diferente

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }

            // Calcula los días de ausencia
            $start = new \DateTime($holiday->start_date);
            $end = new \DateTime($holiday->end_date);
            $daysToUse = $start->diff($end)->days + 1;

            // Comprueba si tiene suficientes días
            if ($user->days < $daysToUse) {
                return response()->json([
                    'success' => false,
                    'message' => 'This employee does not have enough vacation days left.'
                ], 400);
            }
        }

        $holiday->holiday_id = $request->absenceType;
        $holiday->save();

        return response()->json([
            'success' => true,
            'message' => 'Type of absence updated successfully.',
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
                    'message' => 'No employees found for the authenticated user.',
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