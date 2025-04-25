<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    
    /**
     * Muestra la lista de departamentos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $departments = Department::all();
        return view('user.respon_calendar', compact('departments'));
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create($request->all());

        return redirect()->route('department.index')->with('success', 'Departamento creado con éxito.');
    }
}
