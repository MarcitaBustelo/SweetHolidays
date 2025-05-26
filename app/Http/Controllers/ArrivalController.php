<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use Illuminate\Support\Carbon;
use App\Models\Delegation;

class ArrivalController extends Controller
{
    public function index()
    {
        // Cargamos la relación del empleado y, a su vez, su delegación
        $arrivals = Arrival::with(['employee.delegation'])->get();

        foreach ($arrivals as $arrival) {
            // Nombre del empleado
            $arrival->employee_name = $arrival->employee->name ?? '—';

            // Nombre de la delegación (si existe)
            $arrival->delegation_name = $arrival->employee->delegation->name ?? '—';

            // ¿Llegó tarde?
            if ($arrival->arrival_time) {
                $arrivalHour = Carbon::parse($arrival->arrival_time)->format('H:i:s');
                $arrival->late = $arrivalHour > '08:00:00';
            } else {
                $arrival->late = false;
            }
        }

        return view('arrivals.arrivals', compact('arrivals'));
    }

}
