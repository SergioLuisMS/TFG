<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo empleado.
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Almacena un nuevo empleado en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $validated = $request->validate([
            'hora_entrada_contrato' => 'nullable|date_format:H:i',
            'nombre' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:255',
            'primer_apellido' => 'nullable|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'telefono_movil' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'poblacion' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'cumple_dia' => 'nullable|integer|min:1|max:31',
            'cumple_mes' => 'nullable|integer|min:1|max:12',
            'email' => 'nullable|email|max:255',
            'bloqueado' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // Máximo 2MB
        ]);

        // Si se ha subido una foto, la almacenamos en storage/app/public/fotos
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        // Convertimos el checkbox 'bloqueado' en un valor booleano
        $validated['bloqueado'] = $request->has('bloqueado');

        // Creamos el empleado en la base de datos
        Empleado::create($validated);

        // Redirigimos al listado de empleados con un mensaje de éxito
        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente');
    }

    /**
     * Muestra el listado de empleados.
     */
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Muestra el formulario para editar un empleado existente.
     */
    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Actualiza los datos de un empleado existente.
     */
    public function update(Request $request, Empleado $empleado)
    {
        // Validación de los campos del formulario
        $validated = $request->validate([
            'hora_entrada_contrato' => 'nullable|date_format:H:i',
            'nombre' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:255',
            'primer_apellido' => 'nullable|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'telefono_movil' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'poblacion' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'cumple_dia' => 'nullable|integer|min:1|max:31',
            'cumple_mes' => 'nullable|integer|min:1|max:12',
            'email' => 'nullable|email|max:255',
            'bloqueado' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // Máximo 2MB
        ]);

        // Si se ha subido una nueva foto, la almacenamos en storage/app/public/fotos
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        // Convertimos el checkbox 'bloqueado' en un valor booleano
        $validated['bloqueado'] = $request->has('bloqueado');

        // Actualizamos el empleado en la base de datos
        $empleado->update($validated);

        // Redirigimos al listado de empleados con un mensaje de éxito
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }
}
