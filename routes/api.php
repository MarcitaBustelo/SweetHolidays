<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterAPIController;

// Endpoints de autenticación
//Para hacer login
//http://127.0.0.1:8000/api/login?employee_id=10001&password=12345678

//Para hacer register
//http://127.0.0.1:8000/api/login?name=anitaprat&email=anitaprat@gmail.com&password=12345678&c_password=12345678
Route::controller(RegisterAPIController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
