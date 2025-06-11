<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    /**
     * Guarda un comentario asociado a una tarea por un empleado autenticado.
     */
    public function store(Request $request, Tarea $tarea)
    {
        // Obtener el empleado autenticado
        $empleado = Auth::user()->empleado;

        // Verificar que el empleado autenticado es el asignado a la tarea
        if ($tarea->empleado_id !== $empleado->id) {
            abort(403, 'No tienes permiso para comentar esta tarea.');
        }

        // Validación del contenido del comentario
        $validated = $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        // Crear y guardar el comentario en la base de datos
        Comentario::create([
            'tarea_id'     => $tarea->id,
            'empleado_id'  => $empleado->id,
            'contenido'    => $validated['contenido'],
        ]);

        // Redirigir de vuelta con mensaje de éxito
        return redirect()->back()->with('success', 'Comentario añadido correctamente.');
    }

    /**
     * Elimina un comentario si pertenece al empleado autenticado.
     */
    public function destroy(Comentario $comentario)
    {
        // Obtener el empleado autenticado
        $empleado = Auth::user()->empleado;

        // Verificar si el comentario pertenece al empleado
        if ($comentario->empleado_id !== $empleado->id) {
            abort(403, 'No tienes permiso para eliminar este comentario.');
        }

        // Eliminar el comentario
        $comentario->delete();

        // Redirigir de vuelta con mensaje de éxito
        return redirect()->back()->with('success', 'Comentario eliminado.');
    }

    /**
     * Guarda una valoración para un comentario.
     */
    public function valorar(Request $request, Comentario $comentario)
    {
        // Validación de la valoración (de 1 a 5)
        $request->validate([
            'valoracion' => 'required|integer|min:1|max:5'
        ]);

        // Asignar la valoración y guardar
        $comentario->valoracion = $request->valoracion;
        $comentario->save();

        // Redirigir de vuelta con mensaje de éxito
        return back()->with('success', 'Valoración guardada correctamente.');
    }
}
