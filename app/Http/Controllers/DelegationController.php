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
        return view('user.respon_calendar', compact('delegations'));
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

        return redirect()->route('delegation.index')->with('success', 'Delegación creada con éxito.');
    }
}
