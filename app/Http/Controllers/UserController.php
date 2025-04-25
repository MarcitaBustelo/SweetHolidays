<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'employee':
                return view('menu_employee', compact('user'));
            case 'responsable':
                return view('menu_responsable', compact('user'));
            case 'admin':
                return view('menu.admin', compact('user'));
            default:
                return redirect()->route('logout');
        }
    }

    public function showAll()
    {

        $users = User::all();
        return view('user.show', compact('users'));
    }

    public function assignColorsToEmployees()
    {
        // Obtén todos los empleados que aún no tienen un color asignado
        $usersWithoutColor = \App\Models\User::whereNull('color')->get();

        foreach ($usersWithoutColor as $user) {
            // Generar un color único basado en el ID del usuario o aleatoriamente
            $user->color = $this->generateColorFromId($user->id);
            $user->save();
        }

        return response()->json([
            'message' => 'Colores asignados exitosamente a los empleados.',
        ]);
    }

    /**
     * Genera un color único basado en el ID del usuario.
     *
     * @param int $id
     * @return string
     */
    private function generateColorFromId($id)
    {
        // Convierte el ID a un hash hexadecimal y toma los primeros 6 caracteres
        $hash = md5($id);
        return '#' . substr($hash, 0, 6);
    }
}
