<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\ArrivalApiController;


// Endpoints de autenticación
//Para hacer login
//http://127.0.0.1:8000/api/login
// {
//   "employee_id": "10001",
//   "password": "12345678"
// }
Route::post('loginApi', [AuthAPIController::class, 'login']);

//Para registrarse
//http://127.0.0.1:8000/register/register?name=anitaprat&email=anitaprat@gmail.com&NIF=98764523J&delegation_id=1&password=12345678&c_password=12345678
Route::post('register', [AuthAPIController::class, 'register']);

//Para hacer logout
//http://127.0.0.1:8000/api/logout
Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);

// Endpoints de usuario

//Ver ausencias calendario
//http://127.0.0.1:8000/api/user/holidays
Route::middleware('auth:sanctum')->get('user/holidays', [UserApiController::class, 'holiday']);

//Ver perfil
//http://127.0.0.1:8000/api/user/profile
Route::middleware('auth:sanctum')->get('user/profile', [UserApiController::class, 'show']);

//Mandar email
// {
//     "reason": "Family trip to the beach",
//     "start_date": "2025-05-20",
//     "end_date": "2025-05-25"
// }
Route::middleware('auth:sanctum')->post('user/vacation-request', [UserApiController::class, 'sendEmail']);

// QR Code escaner
//http://127.0.0.1:8000/api/user/scan-qr
// {
//     "employee_id": 10001,
//     "timestamp": "2025-05-15T14:25:05"
// }
Route::middleware('auth:sanctum')->post('user/scan-qr', [ArrivalApiController::class, 'handleScan']);
