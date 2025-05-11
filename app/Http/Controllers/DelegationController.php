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
        return view('delegations.delegations', compact('delegations'));
    }

    /**
     * Muestra el formulario para crear una nueva delegación.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('delegation.create');
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

        Delegation::create($request->all());

        return redirect()->route('delegations.delegations')->with('success', 'Delegación creada con éxito.');
    }

    /**
     * Muestra el formulario para editar una delegación existente.
     *
     * @param  \App\Models\Delegation  $delegation
     * @return \Illuminate\View\View
     */
    public function edit(Delegation $delegation)
    {
        return view('delegation.edit', compact('delegation'));
    }

    /**
     * Actualiza una delegación existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delegation  $delegation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Delegation $delegation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $delegation->update($request->all());

        return redirect()->route('delegations.delegations')->with('success', 'Delegación actualizada con éxito.');
    }

    /**
     * Elimina una delegación de la base de datos.
     *
     * @param  \App\Models\Delegation  $delegation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Delegation $delegation)
    {
        $delegation->delete();

        return redirect()->route('delegations.delegations')->with('success', 'Delegación eliminada con éxito.');
    }
}
