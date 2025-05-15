<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\Tarea;
use Illuminate\Support\Carbon;


class TareaController extends Controller
{
    /**
     * Muestra el listado de órdenes con sus tareas filtradas por estado si se proporciona.
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
     * Muestra el formulario para crear una nueva tarea asociada a una orden.
     */
    public function create(Request $request)
    {
        $empleados = \App\Models\Empleado::all();
        $orden = \App\Models\Orden::findOrFail($request->orden);

        return view('tareas.create', compact('empleados', 'orden'));
    }


    /**
     * Almacena una nueva tarea en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $validated = $request->validate([
            'orden_id' => 'required|exists:ordenes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'tiempo_previsto' => 'nullable|integer|min:0',
        ]);

        // Crear la tarea
        \App\Models\Tarea::create($validated);

        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente.');
    }

    // Métodos vacíos generados por el recurso, puedes implementarlos más adelante si los necesitas.
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

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
     * Inicia el cronómetro de una tarea y la marca como En curso.
     */
    public function iniciarCronometro(Tarea $tarea)
    {
        $tarea->cronometro_inicio = now();
        $tarea->estado = 'En curso';
        $tarea->save();

        return back()->with('success', 'Tarea iniciada');
    }

    /**
     * Finaliza el cronómetro de una tarea, guarda el tiempo real trabajado y marca como Finalizada.
     */
    public function finalizarCronometro(Tarea $tarea)
    {
        if ($tarea->cronometro_inicio) {
            $inicio = Carbon::parse($tarea->cronometro_inicio);
            $fin = now();
            $tiempoEnSegundos = $inicio->diffInSeconds($fin);
            $tarea->tiempo_real = $tiempoEnSegundos;
        }

        $tarea->estado = 'Finalizada';
        $tarea->cronometro_inicio = null;
        $tarea->save();

        return back()->with('success', 'Tarea finalizada');
    }

    /**
     * Marca una tarea como En curso e inicia el cronómetro.
     */
    public function marcarEnCurso(Tarea $tarea)
    {
        $tarea->estado = 'En curso';
        $tarea->cronometro_inicio = Carbon::now();
        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea iniciada.');
    }

    /**
     * Finaliza una tarea, calcula el tiempo trabajado y actualiza el estado a Finalizada.
     */
    public function finalizar(Request $request, Tarea $tarea)
    {
        $tarea->tiempo_real = $request->input('tiempo_real');
        $tarea->estado = 'Finalizada';
        $tarea->cronometro_inicio = null; // Detener cronómetro
        $tarea->save();

        return response()->json(['success' => true]);
    }


    public function actualizarTiempo(Request $request, Tarea $tarea)
    {
        $request->validate([
            'tiempo_real' => 'required|regex:/^\d{1,2}:\d{2}:\d{2}$/'
        ]);


        list($horas, $minutos, $segundos) = explode(':', $request->tiempo_real);
        $totalSegundos = ($horas * 3600) + ($minutos * 60) + $segundos;

        $tarea->tiempo_real = $totalSegundos;
        $tarea->save();

        return back()->with('success', 'Tiempo actualizado correctamente.');
    }

    public function guardarTiempo(Request $request, $id)
    {
        $request->validate([
            'tiempo_real' => 'required|integer', // tiempo en segundos
        ]);

        $tarea = Tarea::findOrFail($id);
        $tarea->tiempo_real = $request->tiempo_real;
        $tarea->cronometro_inicio = null; // IMPORTANTE: anular cronómetro al pausar
        $tarea->save();

        return response()->json(['success' => true, 'mensaje' => 'Tiempo actualizado y cronómetro pausado.']);
    }
}
