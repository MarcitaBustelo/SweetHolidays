<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrival;
use App\Models\User;
use Illuminate\Support\Carbon;

class ArrivalController extends Controller
{
    public function index()
    {
        $arrivals = Arrival::with('employee')->get();

        foreach ($arrivals as $arrival) {
            $arrival->employee_name = $arrival->employee->name ?? '—';

            if ($arrival->arrival_time) {
                $arrivalHour = Carbon::parse($arrival->arrival_time)->format('H:i:s');
                if ($arrivalHour > '08:00:00') {
                    $arrival->late = true;
                } else {
                    $arrival->late = false;
                }
            } else {
                $arrival->late = false;
            }
        }

        return view('arrivals.arrivals', compact('arrivals'));
    }
}
