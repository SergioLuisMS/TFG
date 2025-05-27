<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    /**
     * Guarda un comentario asociado a una tarea por un empleado.
     */
    public function store(Request $request, Tarea $tarea)
    {
        $empleado = Auth::user()->empleado;

        // Verificar que el empleado autenticado es el asignado a la tarea
        if ($tarea->empleado_id !== $empleado->id) {
            abort(403, 'No tienes permiso para comentar esta tarea.');
        }

        // Validación del comentario
        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        // Crear el comentario
        Comentario::create([
            'tarea_id' => $tarea->id,
            'empleado_id' => $empleado->id,
            'contenido' => $validated['contenido'],
        ]);

        return redirect()->back()->with('success', 'Comentario añadido correctamente.');
    }

    public function destroy(Comentario $comentario)
    {
        $empleado = Auth::user()->empleado;

        if ($comentario->empleado_id !== $empleado->id) {
            abort(403, 'No tienes permiso para eliminar este comentario.');
        }

        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado.');
    }

    public function valorar(Request $request, Comentario $comentario)
    {
        $request->validate([
            'valoracion' => 'required|integer|min:1|max:5'
        ]);

        $comentario->valoracion = $request->valoracion;
        $comentario->save();

        return back()->with('success', 'Valoración guardada correctamente.');
    }
}
