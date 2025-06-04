<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Cambia el campo de autenticación a employee_id
    public function username()
    {
        return 'employee_id';
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        return match ($user->role) {
            'employee' => route('menu.employee'),
            'responsable' => route('menu.responsable'),
            'admin' => route('menu.admin'),
            default => '/'
        };
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Sobrescribe el método de login para mejor manejo de errores
    public function login(Request $request)
{
    $this->validateLogin($request);

    $credentials = $this->credentials($request);
    $user = \App\Models\User::where('employee_id', $credentials['employee_id'])->first();

    // Verificar si el número de empleado existe
    if (!$user) {
        return redirect()->route('login')
            ->withInput($request->only('employee_id', 'remember'))
            ->withErrors([
                'employee_id' => "This employee doesn't exist",
            ]);
    }

    // Verificar si la contraseña es válida
    if (\Hash::check($credentials['password'], $user->password)) {
        if ($user->active == 0) {
            return redirect()->route('login')
                ->withInput($request->only('employee_id', 'remember'))
                ->withErrors([
                    'employee_id' => "Your account is deactivated",
                ]);
        } elseif ($user->role === 'employee') {
            return redirect()->route('login')
                ->withInput($request->only('employee_id', 'remember'))
                ->withErrors([
                    'employee_id' => "Can't log in because you're not a responsible.",
                ]);
        }

        // Hacer login normal
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            return $this->sendLoginResponse($request);
        }
    }

    return $this->sendFailedLoginResponse($request);
}



    // Método para credenciales personalizado
    protected function credentials(Request $request)
    {
        return [
            'employee_id' => $request->employee_id,
            'password' => $request->password,
        ];
    }

    // Mejorar los mensajes de error
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->route('login')
            ->withInput($request->only('employee_id', 'remember'))
            ->withErrors([
                'employee_id' => __('auth.failed'),
            ]);
    }
}