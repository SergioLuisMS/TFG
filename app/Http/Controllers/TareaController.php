<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\Tarea;
use Illuminate\Support\Carbon;


class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estado = $request->get('estado');
        $ordenes = Orden::with(['tareas' => function ($query) use ($estado) {
            if ($estado) $query->where('estado', $estado);
        }])->get();

        return view('tareas.index', compact('ordenes'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $empleados = \App\Models\Empleado::all();
        $orden = \App\Models\Orden::findOrFail($request->orden);

        return view('tareas.create', [
            'empleados' => $empleados,
            'orden' => $orden
        ]);
    }




    /**
     * Store a newly created resource in storage.
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

        // Redirigir de nuevo al listado de tareas con mensaje de éxito
        return redirect()
            ->route('tareas.index')
            ->with('success', 'Tarea creada correctamente.');
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

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


    public function iniciarCronometro(Tarea $tarea)
    {
        $tarea->cronometro_inicio = now();
        $tarea->estado = 'En curso';
        $tarea->save();

        return back()->with('success', 'Tarea iniciada');
    }

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



    public function marcarEnCurso(Tarea $tarea)
    {
        $tarea->estado = 'En curso';
        $tarea->cronometro_inicio = Carbon::now();
        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea iniciada.');
    }

    public function finalizar(Tarea $tarea)
    {
        if ($tarea->cronometro_inicio) {
            $tarea->tiempo_real = now()->diffInSeconds(Carbon::parse($tarea->cronometro_inicio));
        }
        $tarea->estado = 'Finalizada';
        $tarea->save();

        return redirect()->route('tareas.index')->with('success', 'Tarea finalizada.');
    }
}
