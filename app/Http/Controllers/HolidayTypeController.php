<?php

namespace App\Http\Controllers;

use App\Models\HolidayType;
use Illuminate\Http\Request;

class HolidayTypeController extends Controller
{
    public function index()
    {
        $holiday_types = HolidayType::all();
        return view('holidaystypes.holidaystypes', compact('holiday_types'));
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

        $color = $request->filled('color') ? $request->input('color') : $this->generateRandomColor($request->input('type'));

        $holidayType = HolidayType::create([
            'type' => $request->input('type'),
            'color' => $color
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Type of absence created successfully.',
            'data' => $holidayType,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:255',
        ]);

        $holidayType = HolidayType::findOrFail($id);

        // Verifica que no exista otro registro (distinto al actual) con el mismo nombre (case insensitive)
        $existingType = HolidayType::whereRaw('LOWER(type) = ?', [strtolower($request->input('type'))])
            ->where('id', '!=', $id)
            ->first();

        if ($existingType) {
            return response()->json([
                'success' => false,
                'message' => 'The absence type already exists.',
            ], 422);
        }

        $holidayType->type = $request->input('type');
        // Si se permite actualizar el color manualmente:
        $holidayType->color = $request->filled('color') ? $request->input('color') : $this->generateRandomColor($holidayType->id);

        $holidayType->save();

        return response()->json([
            'success' => true,
            'message' => 'Type of absence updated successfully.',
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

    private function generateRandomColor($seed)
    {
        $hash = md5($seed . microtime());
        return '#' . substr($hash, 0, 6);
    }
}