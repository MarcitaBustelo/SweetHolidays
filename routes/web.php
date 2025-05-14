<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HolidayTypeController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\FestiveController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DelegationController;

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

// RUTAS PARA BOSS Y RHH
// CAMBIAR DEPARTAMENTO Y RESPONSABLE EN VISTA USUARIOS
Route::put('/employees/{id}/update-responsable', [UserController::class, 'updateResponsable'])->name('employees.updateResponsable');
Route::put('/employees/{id}/update-department', [UserController::class, 'updateDepartment'])->name('employees.updateDepartment');


// RUTAS PARA RESPONSABLE 

// MENU RESPONSABLE
Route::get('/responsable', [UserController::class, 'index'])->name('menu.responsable');

// VER PERFIL Y CAMBIAR CONTRASEÑA
Route::get('/profile', [UserController::class, 'show'])->middleware('auth')->name('user.profile');
Route::put('/user/change-password', [ResetPasswordController::class, 'reset'])->name('user.changePassword');

// VER USUARIOS
Route::get('/users', [UserController::class, 'showUsers'])->middleware('auth')->name('user.users');

// EDITAR CUANTOS DIAS DE VACACIONES TIENE CADA EMPLEADO
Route::put('/employees/{id}/update-days', [UserController::class, 'updateDays'])->name('employees.updateDays');

// SOLICITAR VACACIONES(MANDAR CORREO)
Route::post('/send-email/{id}', [UserController::class, 'sendEmail'])->name('holiday_types.send_email');

// VER CALENDARIO VACACIONES Y AÑADIR/EDITAR/ELIMINAR/JUSTIFICAR VACACIONES
Route::get('/responsable/calendar', [UserController::class, 'responCalendar'])->name('user.respon_calendar');
Route::post('/holiday/assign', [HolidayController::class, 'assignHoliday'])->name('holiday.assign');
Route::delete('/holidays/delete', [HolidayController::class, 'deleteHoliday'])->name('holidays.delete');
Route::put('/holiday/update', [HolidayController::class, 'updateHoliday'])->name('holiday.update');
Route::get('/holidays/management', [HolidayController::class, 'showHolidayManagementPage'])->name('holidays.management');
Route::get('/holidays/filter', [HolidayController::class, 'getHolidaysByTypeAndDate'])->name('holidays.getByTypeAndDate');
Route::get('/holidays/{id}', [HolidayController::class, 'getHoliday'])->name('holidays.get');
Route::post('/holidays/edit', [HolidayController::class, 'editJustifyHoliday'])->name('holidays.edit');

// Rutas para HolidayType (Responsable)
Route::get('/holiday_types', [HolidayTypeController::class, 'index'])->name('holiday_types.index');
Route::post('/holiday_types', [HolidayTypeController::class, 'store'])->name('holiday_types.store');
Route::delete('/holiday_types/delete', [HolidayTypeController::class, 'delete'])->name('holiday_types.delete');
Route::post('/holidays/update-type', [HolidayController::class, 'updateType'])->name('holidays.updateType');

// Rutas para Festivos
Route::get('/festives', [FestiveController::class, 'index'])->name('festives.festives');
Route::put('/festives/update-year', [FestiveController::class, 'updateFestiveYear'])->name('festives.updateYear');
Route::put('/festives/{id}/edit-date', [FestiveController::class, 'updateFestive'])->name('festives.editDate');
Route::post('/festives', [FestiveController::class, 'store'])->name('festives.store');
Route::delete('/festives/{id}', [FestiveController::class, 'destroy'])->name('festives.destroy');
Route::put('/festives/{id}', [FestiveController::class, 'update'])->name('festives.update');

//Ruta manual
Route::get('/manual', [ManualController::class, 'show'])->name('manual.show')->middleware('auth');

//Asignar colores a empleados
Route::get('/assign-colors', [HolidayTypeController::class, 'assignColorsToHolidayTypes'])->name('assign.colors');

//Subir excel 
Route::post('/excel/upload', [ExcelController::class, 'processExcelFile'])->name('excel.process');

//Cambiar contraseña
Route::put('/user/change-password', [ResetPasswordController::class, 'reset'])->name('user.changePassword');

// Departamentos
Route::get('/department', [DepartmentController::class, 'index'])->name('departments.departments');
Route::get('create', [DepartmentController::class, 'create'])->name('create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');


// Delegaciones
Route::get('/delegations', [DelegationController::class, 'index'])->name('delegations.delegations');
Route::get('/delegations/create', [DelegationController::class, 'create'])->name('delegations.create');
Route::post('/delegations', [DelegationController::class, 'store'])->name('delegations.store');
Route::get('/delegations/{delegation}/edit', [DelegationController::class, 'edit'])->name('delegations.edit');
Route::put('/delegations/{delegation}', [DelegationController::class, 'update'])->name('delegations.update');
Route::delete('/delegations/{delegation}', [DelegationController::class, 'destroy'])->name('delegations.destroy');
