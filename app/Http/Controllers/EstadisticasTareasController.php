<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EstadisticasTareasController extends Controller
{
    /**
     * Devuelve en JSON el número total de tareas por empleado en el mes actual.
     */
    public function tareasPorEmpleadoMes()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $tareas = Tarea::whereBetween('fecha_inicio', [$inicioMes, $finMes])
            ->selectRaw('empleado_id, COUNT(*) as total')
            ->groupBy('empleado_id')
            ->with('empleado')
            ->get();

        return response()->json($tareas);
    }

    /**
     * Devuelve en JSON la cantidad de órdenes únicas por empleado en el mes actual.
     */
    public function ordenesPorEmpleadoMes()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $ordenes = Tarea::whereBetween('fecha_inicio', [$inicioMes, $finMes])
            ->selectRaw('empleado_id, COUNT(DISTINCT orden_id) as total_ordenes')
            ->groupBy('empleado_id')
            ->with('empleado')
            ->get();

        return response()->json($ordenes);
    }

    /**
     * Devuelve en JSON los datos para graficar la cantidad de tareas por empleado en el mes actual.
     */
    public function datosGraficoTareasMes()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $empleados = Empleado::withCount(['tareas' => function ($query) use ($inicioMes, $finMes) {
            $query->whereBetween('fecha_inicio', [$inicioMes, $finMes]);
        }])->get();

        $labels = $empleados->pluck('nombre');
        $valores = $empleados->pluck('tareas_count');

        return response()->json([
            'labels' => $labels,
            'data'   => $valores,
        ]);
    }

    /**
     * Devuelve en JSON el número de órdenes únicas por empleado para gráficos comparativos.
     */
    public function ordenesMensualesPorEmpleado()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $empleados = Empleado::with(['tareas' => function ($query) use ($inicioMes, $finMes) {
            $query->whereBetween('fecha_inicio', [$inicioMes, $finMes]);
        }])->get();

        $labels = [];
        $data = [];

        foreach ($empleados as $empleado) {
            $labels[] = $empleado->nombre . ' ' . $empleado->primer_apellido;

            // Contar órdenes únicas asignadas en el mes
            $ordenesUnicas = $empleado->tareas->pluck('orden_id')->unique()->count();
            $data[] = $ordenesUnicas;
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }
}
