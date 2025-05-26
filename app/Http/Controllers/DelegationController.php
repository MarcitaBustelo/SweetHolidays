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

        Delegation::create($request->all());

        return redirect()->route('delegations.delegations')->with('success', 'Delegation created successfully');
    }

    public function update(Request $request, Delegation $delegation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $delegation->update($request->all());

        return redirect()->route('delegations.delegations')->with('success', 'Delegation updated successfully');
    }

    public function destroy(Delegation $delegation)
    {
        $delegation->delete();

        return redirect()->route('delegations.delegations')->with('success', 'Delegation deleted correctly');
    }
}
