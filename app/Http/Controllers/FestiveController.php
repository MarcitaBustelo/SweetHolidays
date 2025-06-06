<?php

namespace App\Http\Controllers;

use App\Models\Festive;
use Illuminate\Http\Request;
use App\Models\Delegation;

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
        $delegations = Delegation::all();
        return view('Festives.festives', compact('festives', 'delegations'));
    }



    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'delegation_id' => 'nullable|exists:delegations,id',
            'national' => 'nullable|boolean',
        ], [
            'delegation_id.required_if' => 'A delegation must not be selected when the festive is national.',
        ]);

        // Comprobar si ya existe un festivo con ese nombre (case insensitive)
        $exists = Festive::whereRaw('LOWER(name) = ?', [strtolower($request->name)])->exists();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['name' => 'This festive name already exists.']);
        }

        $festive = new Festive();
        $festive->name = $request->name;
        $festive->date = $request->date;

        if ($request->has('national') && $request->national) {
            $festive->national = true;
            $festive->delegation_id = null;
        } else {
            $festive->national = false;
            $festive->delegation_id = $request->delegation_id;
        }

        $festive->save();

        return redirect()->back()->with('success', 'Festive created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'delegation_id' => 'nullable|exists:delegations,id',
            'national' => 'nullable|boolean',
        ]);

        // Comprobar si ya existe otro festivo con ese nombre (case insensitive, excluyendo el actual)
        $exists = Festive::whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['name' => 'This festive name already exists.']);
        }

        $festive = Festive::findOrFail($id);
        $festive->name = $request->name;
        $festive->date = $request->date;

        if ($request->has('national') && $request->national) {
            $festive->national = true;
            $festive->delegation_id = null;
        } else {
            $festive->national = false;
            $festive->delegation_id = $request->delegation_id;
        }

        $festive->save();

        return redirect()->back()->with('success', 'Festive updated successfully.');
    }
    public function destroy($id)
    {
        $festive = Festive::findOrFail($id);
        $festive->delete();

        return redirect()->back()->with('success', 'Festive deleted successfully.');
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