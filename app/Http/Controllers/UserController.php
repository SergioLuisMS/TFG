<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra los usuarios pendientes de activación.
     */
    public function pendientes()
    {
        $usuarios = User::whereNull('rol')->get();
        $empleados = Empleado::all();

        return view('usuarios.pendientes', compact('usuarios', 'empleados'));
    }

    /**
     * Asigna un rol y un empleado a un usuario.
     */
    public function asignarRol(Request $request, User $user)
    {
        $validated = $request->validate([
            'rol' => 'required|in:admin,empleado',
            'empleado_id' => 'nullable|exists:empleados,id',
        ]);

        $user->rol = $validated['rol'];
        $user->empleado_id = $validated['empleado_id'] ?? null;
        $user->save();

        return redirect()->route('usuarios.pendientes')->with('success', 'Rol asignado correctamente.');
    }
}
