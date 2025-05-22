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
        return view('Departments.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.departments')->with('success', 'Department created successfully.');
    }

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
