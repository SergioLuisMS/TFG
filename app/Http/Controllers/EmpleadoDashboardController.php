<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comentario;

class EmpleadoDashboardController extends Controller
{
    /**
     * Muestra las tareas asignadas al empleado autenticado.
     */
    public function tareas()
    {
        // Obtener el empleado autenticado
        $empleado = Auth::user()->empleado;

        // Verificar si el usuario está vinculado a un empleado
        if (!$empleado) {
            abort(403, 'No estás vinculado a un empleado.');
        }

        // Obtener las tareas asignadas al empleado
        $tareas = $empleado->tareas;

        // Mostrar la vista con las tareas
        return view('empleado.tareas', compact('tareas'));
    }

    /**
     * Muestra el dashboard del empleado con los comentarios valorados del mes actual.
     */
    public function dashboard()
    {
        // Obtener el empleado autenticado
        $empleado = Auth::user()->empleado;

        // Verificar si el usuario está vinculado a un empleado
        if (!$empleado) {
            abort(403, 'No estás vinculado a un empleado.');
        }

        // Obtener los comentarios valorados del mes actual relacionados al empleado
        $comentariosValorados = Comentario::with('tarea.orden')
            ->where('empleado_id', $empleado->id)
            ->whereNotNull('valoracion')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        // Mostrar la vista con los comentarios valorados
        return view('empleado.dashboard', compact('comentariosValorados'));
    }
}
