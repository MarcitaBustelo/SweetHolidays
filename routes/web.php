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
use App\Http\Controllers\Auth\LoginController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
});

// Autenticación
Auth::routes();
Route::get('/logout-safe', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

// RUTAS PARA BOSS Y RHH
// CAMBIAR DEPARTAMENTO Y RESPONSABLE EN VISTA USUARIOS
Route::put('/employees/{id}/update-responsable', [UserController::class, 'updateResponsable'])->name('employees.updateResponsable')->middleware('auth');
Route::put('/employees/{id}/update-department', [UserController::class, 'updateDepartment'])->name('employees.updateDepartment')->middleware('auth');


// RUTAS PARA RESPONSABLE 

// MENU RESPONSABLE
Route::get('/responsable', [UserController::class, 'index'])->name('menu.responsable')->middleware('auth');

// VER PERFIL Y CAMBIAR CONTRASEÑA
Route::get('/profile', [UserController::class, 'show'])->middleware('auth')->name('user.profile')->middleware('auth');
Route::put('/user/change-password', [ResetPasswordController::class, 'reset'])->name('user.changePassword')->middleware('auth');

// VER USUARIOS
Route::get('/users', [UserController::class, 'showUsers'])->middleware('auth')->name('user.users')->middleware('auth');

//ACTIVAR DESACTIVAR
Route::put('/employees/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('employees.toggleActive');

// EDITAR CUANTOS DIAS DE VACACIONES TIENE CADA EMPLEADO
Route::put('/employees/{id}/update-days', [UserController::class, 'updateDays'])->name('employees.updateDays')->middleware('auth');

// SOLICITAR VACACIONES(MANDAR CORREO)
Route::post('/send-email/{id}', [UserController::class, 'sendEmail'])->name('holiday_types.send_email')->middleware('auth');

// VER CALENDARIO VACACIONES Y AÑADIR/EDITAR/ELIMINAR/JUSTIFICAR VACACIONES
Route::get('/responsable/calendar', [UserController::class, 'responCalendar'])->name('user.respon_calendar')->middleware('auth');
Route::post('/holiday/assign', [HolidayController::class, 'assignHoliday'])->name('holiday.assign')->middleware('auth');
Route::delete('/holidays/delete', [HolidayController::class, 'deleteHoliday'])->name('holidays.delete')->middleware('auth');
Route::put('/holiday/update', [HolidayController::class, 'updateHoliday'])->name('holiday.update')->middleware('auth');
Route::get('/holidays/management', [HolidayController::class, 'showHolidayManagementPage'])->name('holidays.management')->middleware('auth');
Route::get('/holidays/filter', [HolidayController::class, 'getHolidaysByTypeAndDate'])->name('holidays.getByTypeAndDate')->middleware('auth');
Route::get('/holidays/{id}', [HolidayController::class, 'getHoliday'])->name('holidays.get')->middleware('auth');
Route::post('/holidays/edit', [HolidayController::class, 'editJustifyHoliday'])->name('holidays.edit')->middleware('auth');

// Rutas para HolidayType (Responsable)
Route::get('/holiday_types', [HolidayTypeController::class, 'index'])->name('holiday_types.index')->middleware('auth');
Route::post('/holiday_types', [HolidayTypeController::class, 'store'])->name('holiday_types.store')->middleware('auth');
Route::delete('/holiday_types/delete', [HolidayTypeController::class, 'delete'])->name('holiday_types.delete')->middleware('auth');
Route::post('/holidays/update-type', [HolidayController::class, 'updateType'])->name('holidays.updateType')->middleware('auth');

Route::get('/festives', function () {
    if (!Auth::user()->hasSpecialAccess()) {
        return redirect()->route('menu.responsable')
            ->with('error', "You can't access festives because you don't have permission.");
    }

    return app(FestiveController::class)->index();
})->name('festives.festives')->middleware('auth');

// Route::get('/festives', [FestiveController::class, 'index'])->name('festives.festives')->middleware('auth');
Route::put('/festives/update-year', [FestiveController::class, 'updateFestiveYear'])->name('festives.updateYear')->middleware('auth');
Route::put('/festives/{id}/edit-date', [FestiveController::class, 'updateFestive'])->name('festives.editDate')->middleware('auth');
Route::post('/festives', [FestiveController::class, 'store'])->name('festives.store')->middleware('auth');
Route::delete('/festives/{id}', [FestiveController::class, 'destroy'])->name('festives.destroy')->middleware('auth');
Route::put('/festives/{id}', [FestiveController::class, 'update'])->name('festives.update')->middleware('auth');

//Ruta manual
Route::get('/manual', [ManualController::class, 'show'])->name('manual.show')->middleware('auth')->middleware('auth');

//Asignar colores a empleados
Route::get('/assign-colors', [HolidayTypeController::class, 'assignColorsToHolidayTypes'])->name('assign.colors')->middleware('auth');

//Subir excel 
Route::post('/excel/upload', [ExcelController::class, 'processExcelFile'])->name('excel.process')->middleware('auth');

//Cambiar contraseña
Route::put('/user/change-password', [ResetPasswordController::class, 'reset'])->name('user.changePassword')->middleware('auth');

Route::get('/department', function () {
    if (!Auth::user()->hasSpecialAccess()) {
        return redirect()->route('menu.responsable')
            ->with('error', "You can't access departments because you don't have permission.");
    }

    return app(DepartmentController::class)->index();
})->name('departments.departments')->middleware('auth');

// Route::get('/department', [DepartmentController::class, 'index'])->name('departments.departments')->middleware('auth');
Route::get('create', [DepartmentController::class, 'create'])->name('create')->middleware('auth');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store')->middleware('auth');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('auth');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update')->middleware('auth');
Route::delete('{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('auth');

Route::get('/delegations', function () {
    if (!Auth::user()->hasSpecialAccess()) {
        return redirect()->route('menu.responsable')
            ->with('error', "You can't access delegations because you don't have permission.");
    }

    return app(DelegationController::class)->index();
})->name('delegations.delegations')->middleware('auth');

// Route::get('/delegations', [DelegationController::class, 'index'])->name('delegations.delegations')->middleware('auth');
Route::get('/delegations/create', [DelegationController::class, 'create'])->name('delegations.create')->middleware('auth');
Route::post('/delegations', [DelegationController::class, 'store'])->name('delegations.store')->middleware('auth');
Route::get('/delegations/{delegation}/edit', [DelegationController::class, 'edit'])->name('delegations.edit')->middleware('auth');
Route::put('/delegations/{delegation}', [DelegationController::class, 'update'])->name('delegations.update')->middleware('auth');
Route::delete('/delegations/{delegation}', [DelegationController::class, 'destroy'])->name('delegations.destroy')->middleware('auth');

//Arrivals
Route::get('/arrivals', [App\Http\Controllers\ArrivalController::class, 'index'])->name('arrivals.index')->middleware('auth');