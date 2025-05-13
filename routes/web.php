<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FaltasController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\TareaController;

/**
 * Personaliza los verbos de las rutas de recursos para usar 'crear' y 'editar' en lugar de 'create' y 'edit'.
 */
Route::resourceVerbs([
    'create' => 'crear',
    'edit' => 'editar',
]);

/**
 * Asegura la singularización de 'ordenes' (aunque esta línea no tiene efecto real si no se aplica en otro contexto).
 */
Str::singular('ordenes');

/**
 * Redirige la ruta principal '/' al dashboard.
 */
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/**
 * Ruta protegida para el dashboard, accesible solo si el usuario está autenticado y verificado.
 */
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/**
 * Agrupa las rutas que requieren autenticación.
 */
Route::middleware('auth')->group(function () {

    /**
     * Gestión del perfil del usuario autenticado.
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Gestión de empleados (excepto mostrar y eliminar, que no están implementados).
     */
    Route::resource('empleados', EmpleadoController::class)->except(['show', 'destroy']);

    /**
     * Registro y visualización de faltas de asistencia.
     */
    Route::get('/faltas', [FaltasController::class, 'index'])->name('asistencias.index');
    Route::post('/faltas', [FaltasController::class, 'store'])->name('faltas.store');

    /**
     * Gráficas individuales y globales de asistencia.
     */
    Route::get('/faltas/grafico/{empleado}', [FaltasController::class, 'grafico'])->name('faltas.grafico');
    Route::get('/faltas/graficas', [FaltasController::class, 'graficasGlobal'])->name('faltas.graficas.global');
    Route::get('/faltas/graficas/datos/{empleado}', [FaltasController::class, 'datosGrafico'])->name('faltas.grafico.datos');
    Route::get('/faltas/graficas/faltas-mensuales/{empleado}', [FaltasController::class, 'faltasAnuales']);

    /**
     * Registro manual de faltas para cualquier fecha.
     */
    Route::get('/faltas/crear', [FaltasController::class, 'crearManual'])->name('faltas.crear.manual');
    Route::post('/faltas/crear', [FaltasController::class, 'guardarManual'])->name('faltas.guardar.manual');

    /**
     * Gráficas de productividad (tareas y órdenes por empleado).
     */
    Route::get('/graficas/tareas-por-empleado', [FaltasController::class, 'tareasPorEmpleadoMes']);
    Route::get('/graficas/ordenes-por-empleado', [FaltasController::class, 'ordenesPorEmpleadoMes']);
    Route::get('/faltas/graficas/tareas-mensuales', [FaltasController::class, 'datosGraficoTareasMes'])->name('faltas.grafico.tareas-mensuales');
    Route::get('/faltas/graficas/ordenes-mensuales', [FaltasController::class, 'ordenesMensualesPorEmpleado'])->name('faltas.grafico.ordenes-mensuales');

    /**
     * Gestión de órdenes.
     */
    Route::resource('ordenes', OrdenController::class)->parameters(['ordenes' => 'orden']);
    Route::get('/ordenes/datos/mensuales', [OrdenController::class, 'datosMensuales'])->name('ordenes.datos.mensuales');

    /**
     * Gestión de tareas.
     */
    Route::resource('tareas', TareaController::class)->parameters(['tareas' => 'tarea']);

    // Repetición para asegurar el registro de rutas personalizadas (puede ser redundante según cómo definas tus resources)
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');

    /**
     * Acciones personalizadas sobre tareas (gestión de estado y cronómetro).
     */
    Route::patch('/tareas/{tarea}/estado', [TareaController::class, 'cambiarEstado'])->name('tareas.cambiarEstado');
    Route::patch('/tareas/{tarea}/iniciar-cronometro', [TareaController::class, 'iniciarCronometro'])->name('tareas.iniciar');
    Route::patch('/tareas/{tarea}/finalizar-cronometro', [TareaController::class, 'finalizarCronometro'])->name('tareas.finalizar');
    Route::post('/tareas/{tarea}/marcar-en-curso', [TareaController::class, 'marcarEnCurso'])->name('tareas.marcarEnCurso');

    Route::post('/tareas/{tarea}/actualizar-tiempo', [TareaController::class, 'actualizarTiempo'])->name('tareas.actualizarTiempo');

});

/**
 * Carga las rutas de autenticación proporcionadas por Breeze o Fortify.
 */
require __DIR__ . '/auth.php';
