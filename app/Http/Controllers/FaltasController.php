<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Falta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\RegistroEntrada;

class FaltasController extends Controller
{
    /**
     * Muestra la vista principal con los empleados y las faltas del día.
     */
    public function index()
    {
        $empleados = Empleado::all();
        $hoy = Carbon::now();

        // Obtener las faltas registradas hoy
        $faltasDeHoy = Falta::where('fecha', $hoy->toDateString())
            ->pluck('empleado_id')
            ->toArray();

        return view('faltas.index', compact('empleados', 'faltasDeHoy'));
    }

    /**
     * Guarda las faltas seleccionadas para el día actual.
     */

    public function store(Request $request)
    {
        $fechaHoy = now()->toDateString();
        $idsMarcados = $request->input('faltas', []);
        $horasEntrada = $request->input('horas_entrada', []);
    
        // Eliminar faltas existentes del día
        Falta::where('fecha', $fechaHoy)->delete();
    
        // Eliminar registros de entrada existentes del día
        RegistroEntrada::where('fecha', $fechaHoy)->delete();
    
        // Registrar nuevas faltas
        foreach ($idsMarcados as $empleadoId) {
            Falta::create([
                'empleado_id' => $empleadoId,
                'fecha'       => $fechaHoy,
            ]);
        }
    
        // Registrar horas de entrada para los que NO han faltado
        foreach ($horasEntrada as $empleadoId => $hora) {
            // Solo si no está marcado como falta y se ha introducido hora válida
            if (!in_array($empleadoId, $idsMarcados) && $hora) {
                RegistroEntrada::create([
                    'empleado_id'       => $empleadoId,
                    'fecha'             => $fechaHoy,
                    'hora_real_entrada' => $hora,
                ]);
            }
        }
    
        return redirect()->route('asistencias.index')
            ->with('success', 'Faltas y registros de entrada actualizados correctamente.');
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

        return redirect()->route('asistencias.index')
            ->with('success', 'Falta asignada correctamente.');
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
}
