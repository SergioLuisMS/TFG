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
    public function index(Request $request)
    {
        $query = Orden::query();

        // Si el usuario seleccionó un campo para ordenar
        if ($request->filled('ordenar_por')) {
            $query->orderByDesc($request->ordenar_por);
        }

        $ordenes = $query->get();

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

        // Validar los campos que están en el fillable
        $validated = $request->validate([
            'fecha_entrada' => 'required|date_format:Y-m-d\TH:i',
            'fecha_salida' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:fecha_entrada',
            'cliente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'matricula' => 'required|string|max:50',
            'vehiculo' => 'nullable|string|max:255',
            'kilometros' => 'nullable|string|max:50',
            'tipo_intervencion' => 'nullable|string|max:255',
            'numero_factura' => 'nullable|string|max:255',
            'numero_presupuesto' => 'nullable|string|max:255',
            'numero_resguardo' => 'nullable|string|max:255',
            'numero_albaran' => 'nullable|string|max:255',
            'situacion_vehiculo' => 'nullable|string|max:255',
            'proxima_itv' => 'nullable|date',
            'numero_bastidor' => 'nullable|string|max:255',
            'descripcion_revision' => 'nullable|string|max:5000',
            'pdf' => 'nullable|mimes:pdf|max:5120',
        ]);


        // Crear la orden con los campos validados
        $orden = Orden::create($validated);

        // Generar el número de orden
        $orden->numero_orden = str_pad($orden->id, 6, '0', STR_PAD_LEFT);

        // Procesar el PDF si se ha subido
        if ($request->hasFile('pdf')) {
            $orden->pdf = $request->file('pdf')->store('pdfs', 'public');
        }

        // Guardar los cambios
        $orden->save();

        return redirect()->route('ordenes.index', request()->query())->with('success', 'Orden actualizada correctamente.');
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
        $validated = $request->validate([
            'fecha_entrada' => 'required|date_format:Y-m-d\TH:i',
            'fecha_salida' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:fecha_entrada',
            'cliente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'matricula' => 'required|string|max:50',
            'vehiculo' => 'nullable|string|max:255',
            'kilometros' => 'nullable|string|max:50',
            'tipo_intervencion' => 'nullable|string|max:255',
            'numero_factura' => 'nullable|string|max:255',
            'numero_presupuesto' => 'nullable|string|max:255',
            'numero_resguardo' => 'nullable|string|max:255',
            'numero_albaran' => 'nullable|string|max:255',
            'situacion_vehiculo' => 'nullable|string|max:255',
            'proxima_itv' => 'nullable|date',
            'numero_bastidor' => 'nullable|string|max:255',
            'descripcion_revision' => 'nullable|string|max:5000',
            'pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Aplicar validación manualmente para asegurar que se guarda todo
        foreach ($validated as $key => $value) {
            $orden->$key = $value;
        }

        // Procesar PDF si se subió uno nuevo
        if ($request->hasFile('pdf')) {
            $orden->pdf = $request->file('pdf')->store('pdfs', 'public');
        }

        // Guardar cambios aunque no haya cambios "dirty"
        $orden->save();

        return redirect()->route('ordenes.index', request()->query())
            ->with('success', 'Orden actualizada correctamente.');
    }



    /**
     * Elimina una orden de la base de datos.
     */
    public function destroy(Orden $orden)
    {
        $orden->delete();
        return redirect()->route('ordenes.index', request()->query())->with('success', 'Orden actualizada correctamente.');
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
