<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    // Muestra el listado de órdenes
    public function index()
    {
        $ordenes = Orden::all();
        return view('ordenes.index', compact('ordenes'));
    }

    public function show(Orden $orden)
    {
        return view('ordenes.show', compact('orden'));
    }


    // Muestra el formulario para crear una nueva orden
    public function create()
    {
        return view('ordenes.create');
    }

    // Guarda la orden creada
    public function store(Request $request)
    {
        // Validar lo necesario
        $request->validate([
            // tus campos requeridos aquí
        ]);

        $orden = new Orden();

        // Asignar otros campos aquí (por ejemplo, $orden->cliente = $request->cliente...)

        $orden->save(); // guardar primero para generar el ID

        // Generar número con ceros a la izquierda
        $orden->numero_orden = str_pad($orden->id, 6, '0', STR_PAD_LEFT);
        $orden->save();

        return redirect()->route('ordenes.index')->with('success', 'Orden creada correctamente.');
    }


    // Muestra el formulario para editar una orden
    public function edit(Orden $orden)
    {
        return view('ordenes.edit', compact('orden'));
    }

    // Actualiza la orden modificada
    public function update(Request $request, Orden $orden)
    {
        $orden->update($request->all());

        return redirect()->route('ordenes.index')->with('success', 'Orden actualizada correctamente.');
    }

    // Eliminar una orden (opcional)
    public function destroy(Orden $orden)
    {
        $orden->delete();
        return redirect()->route('ordenes.index')->with('success', 'Orden eliminada correctamente.');
    }
}
