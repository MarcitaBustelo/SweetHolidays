<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display the list of departments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $departments = Department::all();
        return view('departments.departments', compact('departments'));
    }

    /**
     * Show the form to create a new department.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a new department in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.departments')->with('success', 'Department created successfully.');
    }

    /**
     * Show the form to edit an existing department.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\View\View
     */
    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
    }

    /**
     * Update an existing department in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update($request->all());

        return redirect()->route('departments.departments')->with('success', 'Department updated successfully.');
    }

    /**
     * Delete a department from the database.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.departments')->with('success', 'Department deleted successfully.');
    }
}
