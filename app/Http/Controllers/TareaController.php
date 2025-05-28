<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Orden;
use App\Models\Tarea;
use App\Models\Empleado;

class TareaController extends Controller
{
    /**
     * Muestra el listado de órdenes con sus tareas.
     * Filtra por estado si se proporciona.
     */
    public function index(Request $request)
    {
        $estado = $request->estado;

        if ($estado) {
            $ordenes = Orden::whereHas('tareas', function ($query) use ($estado) {
                $query->where('estado', $estado);
            })->with(['tareas' => function ($query) use ($estado) {
                $query->where('estado', $estado);
            }])->get();
        } else {
            $ordenes = Orden::with('tareas')->get();
        }

        return view('tareas.index', compact('ordenes'));
    }

    /**
     * Muestra el formulario para crear una tarea asociada a una orden.
     */
    public function create(Request $request)
    {
        $orden = Orden::findOrFail($request->orden);
        $empleados = Empleado::all();

        return view('tareas.create', compact('orden', 'empleados'));
    }

    /**
     * Almacena una nueva tarea en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'orden_id' => 'required|exists:ordenes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'tiempo_previsto' => 'nullable|integer|min:0',
        ]);

        Tarea::create($validated);

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Cambia el estado de una tarea entre Asignada -> En curso -> Finalizada.
     */
    public function cambiarEstado(Tarea $tarea)
    {
        if ($tarea->estado === 'Asignada') {
            $tarea->estado = 'En curso';
        } elseif ($tarea->estado === 'En curso') {
            $tarea->estado = 'Finalizada';
        }

        $tarea->save();

        return back()->with('success', 'Estado de la tarea actualizado.');
    }

    /**
     * Inicia el cronómetro y marca como En curso.
     */
    public function iniciarCronometro(Tarea $tarea)
    {
        $tarea->cronometro_inicio = now();
        $tarea->estado = 'En curso';
        $tarea->save();

        return back()->with('success', 'Tarea iniciada');
    }

    /**
     * Finaliza el cronómetro, guarda el tiempo trabajado y marca como Finalizada.
     */
    public function finalizarCronometro(Tarea $tarea)
    {
        if ($tarea->cronometro_inicio) {
            $inicio = Carbon::parse($tarea->cronometro_inicio);
            $tarea->tiempo_real = $inicio->diffInSeconds(now());
        }

        $tarea->estado = 'Finalizada';
        $tarea->cronometro_inicio = null;
        $tarea->save();

        return back()->with('success', 'Tarea finalizada');
    }

    /**
     * Alternativa para marcar tarea como En curso desde botón individual.
     */
    public function marcarEnCurso(Tarea $tarea)
    {
        $tarea->estado = 'En curso';
        $tarea->cronometro_inicio = now();
        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea iniciada.');
    }

    /**
     * Finaliza una tarea desde frontend con tiempo ya calculado (JS).
     */
    public function finalizar(Request $request, Tarea $tarea)
    {
        $tarea->tiempo_real = $request->input('tiempo_real');
        $tarea->estado = 'Finalizada';
        $tarea->cronometro_inicio = null;
        $tarea->save();

        return response()->json(['success' => true]);
    }

    /**
     * Actualiza el tiempo real trabajado manualmente (desde formulario o ajustes).
     */
    public function actualizarTiempo(Request $request, Tarea $tarea)
    {
        $request->validate([
            'tiempo_real' => 'required|regex:/^\d{1,2}:\d{2}:\d{2}$/'
        ]);

        list($h, $m, $s) = explode(':', $request->tiempo_real);
        $tarea->tiempo_real = ($h * 3600) + ($m * 60) + $s;
        $tarea->save();

        return back()->with('success', 'Tiempo actualizado correctamente.');
    }

    /**
     * Guarda el tiempo total en segundos y pausa el cronómetro.
     * Usado por el frontend al hacer clic en "Pausar".
     */
    public function guardarTiempo(Request $request, $id)
    {
        $request->validate([
            'tiempo_real' => 'required|integer',
        ]);

        $tarea = Tarea::findOrFail($id);
        $tarea->tiempo_real = $request->tiempo_real;
        $tarea->cronometro_inicio = null;
        $tarea->save();

        return response()->json(['success' => true, 'mensaje' => 'Tiempo actualizado y cronómetro pausado.']);
    }
}
