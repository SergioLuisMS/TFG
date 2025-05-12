<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenController extends Controller
{
    /**
     * Muestra el listado de todas las órdenes.
     */
    public function index()
    {
        $ordenes = Orden::all();
        return view('ordenes.index', compact('ordenes'));
    }

    /**
     * Muestra el detalle de una orden específica.
     */
    public function show(Orden $orden)
    {
        return view('ordenes.show', compact('orden'));
    }

    /**
     * Muestra el formulario para crear una nueva orden.
     */
    public function create()
    {
        return view('ordenes.create');
    }

    /**
     * Almacena una nueva orden en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación (añade tus reglas si es necesario)
        $request->validate([
            // tus campos requeridos aquí
            'pdf' => 'nullable|mimes:pdf|max:5120', // Opcional, solo PDFs hasta 5MB
        ]);

        // Crea una nueva instancia de orden
        $orden = new Orden();

        // Aquí podrías asignar otros campos del request, por ejemplo:
        // $orden->cliente = $request->cliente;

        // Guarda la orden para obtener su ID
        $orden->save();

        // Genera un número de orden con ceros a la izquierda basado en el ID
        $orden->numero_orden = str_pad($orden->id, 6, '0', STR_PAD_LEFT);

        // Si hay un archivo PDF, lo almacenamos y guardamos la ruta en la orden
        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('pdfs', 'public');
            $orden->pdf = $path;
        }

        // Guarda nuevamente la orden con el número y el pdf si existe
        $orden->save();

        return redirect()->route('ordenes.index')->with('success', 'Orden creada correctamente.');
    }


    /**
     * Muestra el formulario para editar una orden existente.
     */
    public function edit(Orden $orden)
    {
        return view('ordenes.edit', compact('orden'));
    }

    /**
     * Actualiza los datos de una orden existente.
     */
    public function update(Request $request, Orden $orden)
    {
        // Valida los campos que quieres permitir
        $validated = $request->validate([
            // tus otros campos aquí, por ejemplo:
            // 'cliente' => 'required|string|max:255',
            'pdf' => 'nullable|mimes:pdf|max:5120', // PDF opcional, máximo 5MB
        ]);

        // Actualiza los campos permitidos
        $orden->update($validated);

        // Si se ha subido un nuevo PDF, lo almacena y actualiza la ruta
        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('pdfs', 'public');
            $orden->pdf = $path;
            $orden->save();
        }

        return redirect()->route('ordenes.index')->with('success', 'Orden actualizada correctamente.');
    }


    /**
     * Elimina una orden de la base de datos.
     */
    public function destroy(Orden $orden)
    {
        $orden->delete();
        return redirect()->route('ordenes.index')->with('success', 'Orden eliminada correctamente.');
    }

    /**
     * Devuelve en JSON la cantidad de órdenes por mes del año actual.
     * Asegura devolver datos para los 12 meses, incluso si son 0.
     */
    public function datosMensuales()
    {
        // Obtiene el número de órdenes por mes del año actual
        $ordenesPorMes = DB::table('ordenes')
            ->selectRaw('MONTH(fecha_entrada) as mes, COUNT(*) as total')
            ->whereYear('fecha_entrada', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Inicializa el array con 12 valores (uno por mes) en 0
        $datos = array_fill(1, 12, 0);
        foreach ($ordenesPorMes as $registro) {
            $datos[(int) $registro->mes] = $registro->total;
        }

        // Devuelve los datos en formato JSON
        return response()->json([
            'meses' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            'datos' => array_values($datos)
        ]);
    }
}
