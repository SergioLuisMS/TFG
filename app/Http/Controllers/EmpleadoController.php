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
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'primer_apellido' => 'nullable|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'nif' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'telefono_movil' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:10',
            'poblacion' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'cumple_dia' => 'nullable|integer|min:1|max:31',
            'cumple_mes' => 'nullable|integer|min:1|max:12',
            'email' => 'nullable|email|max:255',
            'hora_entrada_contrato' => 'nullable',
            'bloqueado' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // Asegura que es una imagen
        ]);

        $empleado = new \App\Models\Empleado($validated);
        $empleado->bloqueado = $request->has('bloqueado');

        // 📸 GUARDAR FOTO
        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('fotos', 'public'); // -> fotos/ejemplo.jpg
            $empleado->foto = $ruta;
        }

        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
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
         // Paso 1: Confirmar que entra al método
         dd('Entró al método update');
     
         // Paso 2: Mostrar todos los datos que llegan del formulario
         // dd($request->all());
     
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
             'foto' => 'nullable|image|max:2048',
         ]);
     
         // Paso 3: Aplicar cambios y comprobar qué se ha modificado
         $empleado->fill($validated);
         $empleado->bloqueado = $request->has('bloqueado');
     
         // dd($empleado->getDirty()); // Verifica qué campos han cambiado
     
         // Paso 4: Procesar imagen si existe
         if ($request->hasFile('foto')) {
             if ($empleado->foto && Storage::disk('public')->exists($empleado->foto)) {
                 Storage::disk('public')->delete($empleado->foto);
             }
     
             $ruta = $request->file('foto')->store('fotos', 'public');
             $empleado->foto = $ruta;
         }
     
         // Paso 5: Guardar y confirmar
         $empleado->save();
     
         return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
     }
     
}
