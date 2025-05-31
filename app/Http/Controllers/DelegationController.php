<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delegation;

class DelegationController extends Controller
{
    /**
     * Muestra la lista de delegaciones.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $delegations = Delegation::all();
        return view('Delegations.delegations', compact('delegations'));
    }


    /**
     * Almacena una nueva delegación en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $nameWithPrefix = 'SWEET HOLIDAY-' . $request->name;

        $exists = Delegation::whereRaw('LOWER(name) = ?', [strtolower($nameWithPrefix)])->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['name' => 'This delegation already exists.']);
        }

        Delegation::create([
            'name' => $nameWithPrefix,
        ]);

        return redirect()->route('delegations.delegations')->with('success', 'Delegation created successfully');
    }


    public function update(Request $request, Delegation $delegation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Agregar el prefijo al nombre ingresado
        $nameWithPrefix = 'SWEET HOLIDAY-' . $request->name;

        // Comprobar si ya existe otra delegación con ese nombre (case insensitive, excluyendo la actual)
        $exists = Delegation::whereRaw('LOWER(name) = ?', [strtolower($nameWithPrefix)])
            ->where('id', '!=', $delegation->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors(['name' => 'This delegation name already exists.']);
        }

        // Actualizar la delegación con el nombre modificado
        $delegation->update([
            'name' => $nameWithPrefix,
            // Asegúrate de incluir aquí otros campos si los hay
        ]);

        return redirect()->route('delegations.delegations')->with('success', 'Delegation updated successfully');
    }


    public function destroy(Delegation $delegation)
    {
        $delegation->delete();

        return redirect()->route('delegations.delegations')->with('success', 'Delegation deleted correctly');
    }
}
