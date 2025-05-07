<?php

namespace App\Http\Controllers;

use App\Models\Festive;
use Illuminate\Http\Request;

class FestiveController extends Controller
{

    /**
     * Muestra la lista de festivos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $festives = Festive::all();
        return view('festives.festives', compact('festives'));
    }

    public function updateFestiveYear()
    {
        $festives = Festive::all();

        foreach ($festives as $festive) {
            $currentDate = new \DateTime($festive->date);
            $currentDate->modify('+1 year');
            $festive->date = $currentDate->format('Y-m-d');
            $festive->save();
        }

        return response()->json(['success' => true, 'message' => 'Festive dates updated to the next year.']);
    }

    public function updateFestive(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $festive = Festive::findOrFail($id);
        $festive->date = $request->input('date');
        $festive->save();

        return redirect()->route('festives.festives')->with('success', 'Fecha del festivo actualizada correctamente.');
    }
}