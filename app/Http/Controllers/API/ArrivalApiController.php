<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arrival;
use Carbon\Carbon;

class ArrivalApiController extends Controller
{
    public function handleScan(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,employee_id',
        ]);

        $employeeId = $request->input('employee_id');
        $currentDateTime = Carbon::now('Europe/Madrid');
        $currentDate = $currentDateTime->format('Y-m-d');
        $currentTime = $currentDateTime->format('H:i:s');


        $arrival = Arrival::where('employee_id', $employeeId)
            ->where('date', $currentDate)
            ->first();

        if (!$arrival) {
            $arrival = new Arrival();
            $arrival->employee_id = $employeeId;
            $arrival->date = $currentDate;
            $arrival->arrival_time = $currentTime;
            $arrival->save();

            return response()->json([
                'success' => true,
                'message' => 'Arrival time recorded successfully.',
                'data' => $arrival,
            ], 201);
        }

        if ($arrival->departure_time === null) {
            $arrival->departure_time = $currentTime;
            $arrival->save();

            return response()->json([
                'success' => true,
                'message' => 'Departure time recorded successfully.',
                'data' => $arrival,
            ], 200);
        }

        // Si ya tiene llegada y salida registradas
        return response()->json([
            'success' => false,
            'message' => 'Both arrival and departure times are already recorded for today.',
        ], 400);
    }
}
