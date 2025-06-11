<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Falta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Tarea;
use App\Models\RegistroEntrada;

class FaltasController extends Controller
{
    /**
     * Muestra la vista principal con los empleados, faltas y horas de entrada del día.
     */
    public function index()
    {
        $empleados = Empleado::all();
        $hoy = Carbon::now();

        // Obtener las horas reales de entrada de hoy por empleado
        $horasEntradaDeHoy = RegistroEntrada::where('fecha', $hoy->toDateString())
            ->pluck('hora_real_entrada', 'empleado_id')
            ->toArray();

        // Generar días laborables del mes actual
        $diasMes = collect();
        $dia = $hoy->copy()->startOfMonth();
        while ($dia->month === $hoy->month) {
            if ($dia->isWeekday()) $diasMes->push($dia->copy());
            $dia->addDay();
        }

        // Faltas registradas hoy
        $faltasDeHoy = Falta::where('fecha', $hoy->toDateString())
            ->pluck('empleado_id')
            ->toArray();

        // Obtener historial de registros
        $registros = RegistroEntrada::with('empleado')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('faltas.index', compact(
            'empleados',
            'diasMes',
            'faltasDeHoy',
            'horasEntradaDeHoy',
            'registros'
        ));
    }

    /**
     * Guarda las faltas y entradas del día actual.
     */
    public function store(Request $request)
    {
        $fechaHoy = now()->toDateString();
        $idsMarcados = $request->input('faltas', []);
        $horasEntrada = $request->input('horas_entrada', []);

        // Eliminar faltas previas del día
        Falta::where('fecha', $fechaHoy)->delete();

        // Registrar nuevas faltas
        foreach ($idsMarcados as $empleadoId) {
            Falta::create([
                'empleado_id' => $empleadoId,
                'fecha' => $fechaHoy,
            ]);
        }

        // Guardar horas reales de entrada si se proporcionaron
        foreach ($horasEntrada as $empleadoId => $hora) {
            if ($hora) {
                RegistroEntrada::updateOrCreate(
                    ['empleado_id' => $empleadoId, 'fecha' => $fechaHoy],
                    ['hora_real_entrada' => $hora]
                );
            }
        }

        return redirect()->route('asistencias.index')
            ->with('success', 'Faltas y entradas guardadas correctamente.');
    }

    /**
     * Muestra una gráfica de asistencia de un empleado frente al promedio del equipo.
     */
    public function grafico(Empleado $empleado)
    {
        // Obtener registros del mes actual
        $registros = RegistroEntrada::where('empleado_id', $empleado->id)
            ->whereMonth('fecha', now()->month)
            ->get();

        // Hora contratada de entrada
        $horaContrato = $empleado->hora_entrada_contrato
            ? Carbon::createFromFormat('H:i:s', $empleado->hora_entrada_contrato)
            : null;

        $totalMinutosRetraso = 0;
        $diasConRetraso = 0;

        // Calcular retrasos
        if ($horaContrato && $registros->count() > 0) {
            foreach ($registros as $registro) {
                $horaReal = Carbon::createFromFormat('H:i:s', $registro->hora_real_entrada);
                if ($horaReal->gt($horaContrato)) {
                    $totalMinutosRetraso += $horaContrato->diffInMinutes($horaReal);
                    $diasConRetraso++;
                }
            }
        }

        $hoy = now();
        $inicio = $hoy->copy()->startOfMonth();
        $fin = $hoy->copy();

        // Días laborables del mes
        $diasLaborables = collect();
        $dia = $inicio->copy();
        while ($dia <= $fin) {
            if ($dia->isWeekday()) $diasLaborables->push($dia->toDateString());
            $dia->addDay();
        }

        $totalDias = $diasLaborables->count();

        // Faltas del empleado
        $faltasEmpleado = Falta::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$inicio, $fin])
            ->pluck('fecha')
            ->toArray();

        $totalFaltas = count($faltasEmpleado);
        $totalAsistencias = $totalDias - $totalFaltas;
        $porcentajeEmpleado = $totalDias > 0
            ? round(($totalAsistencias / $totalDias) * 100, 2)
            : 0;

        // Media general
        $empleados = Empleado::all();
        $porcentajes = $empleados->map(function ($e) use ($inicio, $fin, $totalDias) {
            $faltas = Falta::where('empleado_id', $e->id)
                ->whereBetween('fecha', [$inicio, $fin])
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
            'diferencia',
            'totalMinutosRetraso',
            'diasConRetraso'
        ));
    }

    /**
     * Muestra el resumen general para gráficos del primer empleado.
     */
    public function graficasGlobal()
    {
        $empleados = Empleado::all();
        $empleado = $empleados->first();

        $asistencias = 0;
        $faltas = 0;
        $porcentaje = 0;
        $totalMinutosRetraso = 0;
        $diasConRetraso = 0;

        if ($empleado) {
            $hoy = now();
            $inicioMes = $hoy->copy()->startOfMonth();
            $finMes = $hoy->copy();

            // Días laborables
            $diasLaborables = collect();
            $dia = $inicioMes->copy();
            while ($dia <= $finMes) {
                if ($dia->isWeekday()) $diasLaborables->push($dia->toDateString());
                $dia->addDay();
            }

            $totalDias = $diasLaborables->count();

            // Faltas
            $faltasEmpleado = Falta::where('empleado_id', $empleado->id)
                ->whereBetween('fecha', [$inicioMes, $finMes])
                ->pluck('fecha')
                ->toArray();

            $totalFaltas = count($faltasEmpleado);
            $asistencias = $totalDias - $totalFaltas;
            $faltas = $totalFaltas;
            $porcentaje = $totalDias > 0 ? round(($asistencias / $totalDias) * 100, 2) : 0;

            // Retrasos
            $registros = RegistroEntrada::where('empleado_id', $empleado->id)
                ->whereMonth('fecha', now()->month)
                ->get();

            $horaContrato = $empleado->hora_entrada_contrato
                ? Carbon::createFromFormat('H:i:s', $empleado->hora_entrada_contrato)
                : null;

            if ($horaContrato) {
                foreach ($registros as $registro) {
                    $horaReal = Carbon::createFromFormat('H:i:s', $registro->hora_real_entrada);
                    if ($horaReal->gt($horaContrato)) {
                        $totalMinutosRetraso += $horaContrato->diffInMinutes($horaReal);
                        $diasConRetraso++;
                    }
                }
            }
        }

        return view('faltas.graficasGlobal', compact(
            'empleados',
            'empleado',
            'asistencias',
            'faltas',
            'porcentaje',
            'totalMinutosRetraso',
            'diasConRetraso'
        ));
    }
    /**
     * Devuelve datos en JSON para la gráfica individual de un empleado.
     */
    public function datosGrafico(Empleado $empleado)
    {
        // Registros de entrada del mes actual
        $registros = RegistroEntrada::where('empleado_id', $empleado->id)
            ->whereMonth('fecha', now()->month)
            ->get();

        $horaContrato = $empleado->hora_entrada_contrato
            ? Carbon::createFromFormat('H:i:s', $empleado->hora_entrada_contrato)
            : null;

        $totalMinutosRetraso = 0;
        $diasConRetraso = 0;

        // Cálculo de retrasos
        if ($horaContrato && $registros->count() > 0) {
            foreach ($registros as $registro) {
                $horaReal = Carbon::createFromFormat('H:i:s', $registro->hora_real_entrada);
                if ($horaReal->gt($horaContrato)) {
                    $totalMinutosRetraso += $horaContrato->diffInMinutes($horaReal);
                    $diasConRetraso++;
                }
            }
        }

        // Fechas del mes actual y anterior
        $hoy = now();
        $inicioMesActual = $hoy->copy()->startOfMonth();
        $finMesActual = $hoy->copy();
        $inicioMesAnterior = $hoy->copy()->subMonth()->startOfMonth();
        $finMesAnterior = $hoy->copy()->subMonth()->endOfMonth();

        // Días laborables del mes actual
        $diasLaborables = collect();
        $dia = $inicioMesActual->copy();
        while ($dia <= $finMesActual) {
            if ($dia->isWeekday()) $diasLaborables->push($dia->toDateString());
            $dia->addDay();
        }

        $totalDias = $diasLaborables->count();

        // Faltas actuales y anteriores
        $faltasActual = Falta::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$inicioMesActual, $finMesActual])
            ->pluck('fecha')
            ->toArray();

        $totalFaltas = count($faltasActual);
        $totalAsistencias = $totalDias - $totalFaltas;
        $porcentaje = $totalDias > 0 ? round(($totalAsistencias / $totalDias) * 100, 2) : 0;

        $faltasAnterior = Falta::where('empleado_id', $empleado->id)
            ->whereBetween('fecha', [$inicioMesAnterior, $finMesAnterior])
            ->count();

        // Promedio de asistencia general
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
            'nombre'              => $empleado->nombre . ' ' . $empleado->primer_apellido,
            'asistencias'         => $totalAsistencias,
            'faltas'              => $totalFaltas,
            'porcentaje'          => $porcentaje,
            'media'               => $media,
            'diferenciaPorcentaje'=> $diferenciaPorcentaje,
            'foto'                => $empleado->foto,
            'diferenciaFaltas'    => $faltasAnterior - $totalFaltas,
            'faltasMesAnterior'   => $faltasAnterior,
            'minutosRetraso'      => $totalMinutosRetraso,
            'diasConRetraso'      => $diasConRetraso,
        ]);
    }

    /**
     * Devuelve en JSON la cantidad de faltas por mes (enero a diciembre) del empleado.
     */
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
            'meses'  => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            'faltas' => $faltasPorMes
        ]);
    }

    /**
     * Muestra el formulario para asignar faltas manualmente.
     */
    public function crearManual()
    {
        $empleados = Empleado::all();
        return view('faltas.crearManual', compact('empleados'));
    }

    /**
     * Guarda una falta manual validando que no esté duplicada.
     */
    public function guardarManual(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha'       => 'required|date',
        ]);

        // Evitar duplicados
        $existe = Falta::where('empleado_id', $request->empleado_id)
            ->where('fecha', $request->fecha)
            ->exists();

        if (!$existe) {
            Falta::create([
                'empleado_id' => $request->empleado_id,
                'fecha'       => $request->fecha,
            ]);
        }

        return redirect()->route('asistencias.index')->with('success', 'Falta asignada correctamente.');
    }

    /**
     * Devuelve en JSON la cantidad total de tareas por empleado en el mes actual.
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
     * Devuelve en JSON el número de órdenes únicas por empleado en el mes actual.
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
     * Devuelve en JSON la cantidad de tareas por empleado para representar en gráficas.
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
     * Devuelve en JSON la cantidad de órdenes únicas por empleado del mes actual.
     */
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

            $ordenesUnicas = $empleado->tareas->pluck('orden_id')->unique()->count();
            $data[] = $ordenesUnicas;
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $data,
        ]);
    }

    /**
     * Permite actualizar la hora de entrada de un registro específico.
     */
    public function actualizarHora(Request $request, $id)
    {
        $request->validate([
            'hora_entrada' => 'required|date_format:H:i',
        ]);

        $registro = RegistroEntrada::findOrFail($id);
        $registro->hora_entrada = $request->hora_entrada;
        $registro->save();

        return back()->with('success', 'Hora actualizada correctamente.');
    }
}
