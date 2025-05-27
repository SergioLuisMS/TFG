<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comentario;

class EmpleadoDashboardController extends Controller
{
    /**
     * Muestra las tareas asignadas al empleado actual.
     */
    public function tareas()
    {
        $empleado = Auth::user()->empleado;

        if (!$empleado) {
            abort(403, 'No estás vinculado a un empleado.');
        }

        $tareas = $empleado->tareas;

        return view('empleado.tareas', compact('tareas'));
    }

    /**
     * Muestra el dashboard del empleado con comentarios valorados del mes.
     */
    public function dashboard()
    {
        $empleado = Auth::user()->empleado;

        if (!$empleado) {
            abort(403, 'No estás vinculado a un empleado.');
        }

        $comentariosValorados = Comentario::with('tarea.orden')
            ->where('empleado_id', $empleado->id)
            ->whereNotNull('valoracion')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        return view('empleado.dashboard', compact('comentariosValorados'));
    }
}
