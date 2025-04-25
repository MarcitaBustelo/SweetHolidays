<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Models\Responsable;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HolidayTypeController;
use App\Http\Controllers\ManualController;


// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Autenticación
Auth::routes();

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'responsable') {
            return redirect()->route('menu.responsable');
        } else if (Auth::user()->role === 'employee') {
            return redirect()->route('menu_employee');
        } else if (Auth::user()->role === 'admin') {
            return redirect()->route('menu.admin');
        }
    }
    return view('welcome');
})->name('login');

//rutas para empleados
Route::get('/employee', [UserController::class, 'index'])->name('menu.employee');
Route::get('/profile', [EmployeeController::class, 'show'])->middleware('auth')->name('user.profile');
Route::get('/users', [EmployeeController::class, 'index'])->middleware('auth')->name('user.users');
Route::put('/employees/{id}/update-days', [EmployeeController::class, 'updateDays'])->name('employees.updateDays');
Route::get('/calendar', [EmployeeController::class, 'holiday'])->middleware('auth')->name('user.calendar');
Route::post('/send-email/{id}', [EmployeeController::class, 'sendEmail'])->name('holiday_types.send_email');


//rutas para responsable
Route::get('/responsable', [UserController::class, 'index'])->name('menu.responsable');
Route::get('/responsable/calendar', [ResponsableController::class, 'responCalendar'])->name('user.respon_calendar');
Route::post('/holiday/assign', [HolidayController::class, 'assignHoliday'])->name('holiday.assign');
Route::delete('/holidays/delete', [HolidayController::class, 'deleteHoliday'])->name('holidays.delete');
Route::put('/holiday/update', [HolidayController::class, 'updateHoliday'])->name('holiday.update');

// Rutas para HolidayType (Responsable)
Route::get('/holiday_types', [HolidayTypeController::class, 'index'])->name('holiday_types.index'); 
Route::post('/holiday_types', [HolidayTypeController::class, 'store'])->name('holiday_types.store');
Route::delete('/holiday_types/delete', [HolidayTypeController::class, 'delete'])->name('holiday_types.delete');


Route::get('/manual', [ManualController::class, 'show'])->name('manual.show')->middleware('auth');

//Asignar colores a empleados
Route::get('/assign-colors', [UserController::class, 'assignColorsToEmployees'])->name('assign.colors');