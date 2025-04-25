<?php

namespace App\Http\Controllers;

use App\Models\HolidayType;
use Illuminate\Http\Request;

class HolidayTypeController extends Controller
{
    /**
     */
    public function index()
    {
        $holiday_types = HolidayType::all();
        return response()->json($holiday_types);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
        ]);

        $existingType = HolidayType::whereRaw('LOWER(type) = ?', [strtolower($request->input('type'))])->first();

        if ($existingType) {
            return response()->json([
                'success' => false,
                'message' => 'El tipo de ausencia ya existe.',
            ], 422);
        }

        $holidayType = HolidayType::create([
            'type' => $request->input('type'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de vacaciones creado exitosamente.',
            'data' => $holidayType,
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:holidays_types,id',
        ]);

        $holidayType = HolidayType::find($request->input('id'));
        $holidayType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de vacaciones eliminado exitosamente.',
        ]);
    }
}
