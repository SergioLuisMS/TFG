<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $ordenes = \App\Models\Orden::with(['tareas.empleado'])->get(['id', 'numero_orden', 'matricula']);
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
}
