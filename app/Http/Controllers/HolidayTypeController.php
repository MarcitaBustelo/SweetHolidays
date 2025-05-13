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
                'message' => 'The absence type already exists.',
            ], 422);
        }

        $holidayType = HolidayType::create([
            'type' => $request->input('type'),
        ]);

        $holidayType->color = $this->generateRandomColor($holidayType->id);
        $holidayType->save();

        return response()->json([
            'success' => true,
            'message' => 'Type of absence created successfully.',
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
            'message' => 'Type of absence deleted successfully.',
        ]);
    }

    private function generateRandomColor($id)
    {
        $hash = md5($id);
        return '#' . substr($hash, 0, 6); // Tomar los primeros 6 caracteres del hash para el color
    }
}