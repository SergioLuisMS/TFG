<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Falta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Tarea;


class FaltasController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        $hoy = Carbon::now();

        $diasMes = collect();
        $dia = $hoy->copy()->startOfMonth();
        while ($dia->month === $hoy->month) {
            if ($dia->isWeekday()) $diasMes->push($dia->copy());
            $dia->addDay();
        }

        $faltasDeHoy = Falta::where('fecha', $hoy->toDateString())->pluck('empleado_id')->toArray();

        return view('faltas.index', compact('empleados', 'diasMes', 'faltasDeHoy'));
    }

    public function store(Request $request)
    {
        $fechaHoy = now()->toDateString();
        $idsMarcados = $request->input('faltas', []);

        Falta::where('fecha', $fechaHoy)->delete();
        foreach ($idsMarcados as $empleadoId) {
            Falta::create([
                'empleado_id' => $empleadoId,
                'fecha' => $fechaHoy,
            ]);
        }

        return redirect()->route('asistencias.index')->with('success', 'Faltas guardadas correctamente.');
    }

    public function grafico(Empleado $empleado)
    {
        $hoy = now();
        $inicio = $hoy->copy()->startOfMonth();
        $fin = $hoy->copy();

        $diasLaborables = collect();
        $dia = $inicio->copy();
        while ($dia <= $fin) {
            if ($dia->isWeekday()) $diasLaborables->push($dia->toDateString());
            $dia->addDay();
        }

        $totalDias = $diasLaborables->count();

        $faltasEmpleado = Falta::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
            ->pluck('fecha')->toArray();

        $totalFaltas = count($faltasEmpleado);
        $totalAsistencias = $totalDias - $totalFaltas;
        $porcentajeEmpleado = $totalDias > 0 ? round(($totalAsistencias / $totalDias) * 100, 2) : 0;

        $empleados = Empleado::all();
        $porcentajes = $empleados->map(function ($e) use ($inicio, $fin, $totalDias) {
            $faltas = Falta::where('empleado_id', $e->id)
                ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                ->count();
            $asistencias = $totalDias - $faltas;
            return $totalDias > 0 ? ($asistencias / $totalDias) * 100 : 0;
        });

        $mediaGeneral = round($porcentajes->average(), 2);
        $diferencia = round($porcentajeEmpleado - $mediaGeneral, 2);

        return view('faltas.grafico', compact(
            'empleado',
            'totalDias',
            'totalFaltas',
            'totalAsistencias',
            'porcentajeEmpleado',
            'mediaGeneral',
            'diferencia'
        ));
    }

    public function graficasGlobal()
    {
        $empleados = Empleado::all();
        return view('faltas.graficasGlobal', compact('empleados'));
    }

    public function datosGrafico(Empleado $empleado)
    {
        $hoy = now();

        $inicioMesActual = $hoy->copy()->startOfMonth();
        $finMesActual = $hoy->copy();

        $inicioMesAnterior = $hoy->copy()->subMonth()->startOfMonth();
        $finMesAnterior = $hoy->copy()->subMonth()->endOfMonth();

        $diasLaborables = collect();
        $dia = $inicioMesActual->copy();
        while ($dia <= $finMesActual) {
            if ($dia->isWeekday()) $diasLaborables->push($dia->toDateString());
            $dia->addDay();
        }

        $totalDias = $diasLaborables->count();

        $faltasActual = Falta::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$inicioMesActual, $finMesActual])
            ->pluck('fecha')->toArray();

        $totalFaltas = count($faltasActual);
        $totalAsistencias = $totalDias - $totalFaltas;
        $porcentaje = $totalDias > 0 ? round(($totalAsistencias / $totalDias) * 100, 2) : 0;

        $faltasAnterior = Falta::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$inicioMesAnterior, $finMesAnterior])
            ->count();

        $empleados = Empleado::all();
        $porcentajes = $empleados->map(function ($e) use ($inicioMesActual, $finMesActual, $totalDias) {
            $faltas = Falta::where('empleado_id', $e->id)
                ->whereBetween('fecha', [$inicioMesActual, $finMesActual])
                ->count();
            $asistencias = $totalDias - $faltas;
            return $totalDias > 0 ? ($asistencias / $totalDias) * 100 : 0;
        });

        $media = round($porcentajes->average(), 2);
        $diferenciaPorcentaje = round($porcentaje - $media, 2);

        return response()->json([
            'nombre' => $empleado->nombre . ' ' . $empleado->primer_apellido,
            'asistencias' => $totalAsistencias,
            'faltas' => $totalFaltas,
            'porcentaje' => $porcentaje,
            'media' => $media,
            'diferenciaPorcentaje' => $diferenciaPorcentaje,
            'foto' => $empleado->foto,
            'diferenciaFaltas' => $faltasAnterior - $totalFaltas,
            'faltasMesAnterior' => $faltasAnterior
        ]);
    }

    // NUEVO: Devuelve las faltas por mes de enero a diciembre del año actual
    public function faltasAnuales(Empleado $empleado)
    {
        $faltasPorMes = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            $inicio = Carbon::create(now()->year, $mes, 1)->startOfMonth();
            $fin = $inicio->copy()->endOfMonth();

            $faltas = Falta::where('empleado_id', $empleado->id)
                ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                ->count();

            $faltasPorMes[] = $faltas;
        }

        return response()->json([
            'meses' => [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre'
            ],
            'faltas' => $faltasPorMes
        ]);
    }

    public function crearManual()
    {
        $empleados = Empleado::all();
        return view('faltas.crearManual', compact('empleados'));
    }

    public function guardarManual(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
        ]);

        $existe = Falta::where('empleado_id', $request->empleado_id)
            ->where('fecha', $request->fecha)
            ->exists();

        if (!$existe) {
            Falta::create([
                'empleado_id' => $request->empleado_id,
                'fecha' => $request->fecha,
            ]);
        }

        return redirect()->route('asistencias.index')->with('success', 'Falta asignada correctamente.');
    }

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
            'data' => $valores,
        ]);
    }

    public function ordenesMensualesPorEmpleado()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $empleados = Empleado::with(['tareas' => function ($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_inicio', [$inicioMes, $finMes]);
        }])->get();

        $labels = [];
        $data = [];

        foreach ($empleados as $empleado) {
            $labels[] = $empleado->nombre . ' ' . $empleado->primer_apellido;

            // Distintos orden_id en las tareas de este mes
            $ordenesUnicas = $empleado->tareas->pluck('orden_id')->unique()->count();

            $data[] = $ordenesUnicas;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
