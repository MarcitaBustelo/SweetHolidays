<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExcelController extends Controller
{

    public function processExcelFile(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new UsersImport, $request->file('excelFile'));

        return redirect()->back()->with('success', 'Users imported successfully.');
    }
    
}