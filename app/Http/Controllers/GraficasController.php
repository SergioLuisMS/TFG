<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Falta;
use App\Models\RegistroEntrada;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GraficasController extends Controller
{
    /**
     * Muestra la gráfica de asistencia de un empleado en comparación con la media general.
     */
    public function grafico(Empleado $empleado)
    {
        // Obtener registros del mes actual
        $registros = RegistroEntrada::where('empleado_id', $empleado->id)
            ->whereMonth('fecha', now()->month)
            ->get();

        $horaContrato = $empleado->hora_entrada_contrato
            ? Carbon::createFromFormat('H:i:s', $empleado->hora_entrada_contrato)
            : null;

        $totalMinutosRetraso = 0;
        $diasConRetraso = 0;

        // Calcular retrasos
        if ($horaContrato) {
            foreach ($registros as $registro) {
                $horaReal = Carbon::createFromFormat('H:i:s', $registro->hora_real_entrada);
                if ($horaReal->gt($horaContrato)) {
                    $totalMinutosRetraso += $horaContrato->diffInMinutes($horaReal);
                    $diasConRetraso++;
                }
            }
        }

        // Calcular días laborables del mes
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

        // Media general del equipo
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
     * Muestra una vista global con datos de asistencia y retrasos del primer empleado.
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

            // Días laborables del mes
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
     * Devuelve en JSON los datos estadísticos individuales de un empleado.
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

        if ($horaContrato) {
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

        // Promedio del equipo
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
            'nombre'                => $empleado->nombre . ' ' . $empleado->primer_apellido,
            'asistencias'           => $totalAsistencias,
            'faltas'                => $totalFaltas,
            'porcentaje'            => $porcentaje,
            'media'                 => $media,
            'diferenciaPorcentaje'  => $diferenciaPorcentaje,
            'foto'                  => $empleado->foto,
            'diferenciaFaltas'      => $faltasAnterior - $totalFaltas,
            'faltasMesAnterior'     => $faltasAnterior,
            'minutosRetraso'        => $totalMinutosRetraso,
            'diasConRetraso'        => $diasConRetraso,
        ]);
    }
}
