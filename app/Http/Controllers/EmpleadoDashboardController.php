<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoDashboardController extends Controller
{
    public function tareas()
    {
        $empleado = Auth::user()->empleado;

        if (!$empleado) {
            abort(403, 'No estás vinculado a un empleado.');
        }

        $tareas = $empleado->tareas;

        return view('empleado.tareas', compact('tareas'));
    }
}
