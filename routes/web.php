<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FaltasController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\TareaController;

// Corrige la singularización de recursos
Route::resourceVerbs([
    'create' => 'crear',
    'edit' => 'editar',
]);

Str::singular('ordenes');

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Empleados
    Route::resource('empleados', EmpleadoController::class)->except(['show', 'destroy']);

    // Faltas
    Route::get('/faltas', [FaltasController::class, 'index'])->name('asistencias.index');
    Route::post('/faltas', [FaltasController::class, 'store'])->name('faltas.store');
    Route::get('/faltas/grafico/{empleado}', [FaltasController::class, 'grafico'])->name('faltas.grafico');
    Route::get('/faltas/graficas', [FaltasController::class, 'graficasGlobal'])->name('faltas.graficas.global');
    Route::get('/faltas/graficas/datos/{empleado}', [FaltasController::class, 'datosGrafico'])->name('faltas.grafico.datos');
    Route::get('/faltas/crear', [FaltasController::class, 'crearManual'])->name('faltas.crear.manual');
    Route::post('/faltas/crear', [FaltasController::class, 'guardarManual'])->name('faltas.guardar.manual');
    Route::get('/faltas/graficas/faltas-mensuales/{empleado}', [FaltasController::class, 'faltasAnuales']);

    // Gráficas de tareas y órdenes
    Route::get('/graficas/tareas-por-empleado', [FaltasController::class, 'tareasPorEmpleadoMes']);
    Route::get('/graficas/ordenes-por-empleado', [FaltasController::class, 'ordenesPorEmpleadoMes']);
    Route::get('/faltas/graficas/tareas-mensuales', [FaltasController::class, 'datosGraficoTareasMes'])->name('faltas.grafico.tareas-mensuales');
    Route::get('/faltas/graficas/ordenes-mensuales', [FaltasController::class, 'ordenesMensualesPorEmpleado']);

    Route::get('/faltas/graficas/ordenes-mensuales', [FaltasController::class, 'ordenesMensualesPorEmpleado'])->name('faltas.grafico.ordenes-mensuales');

    // Órdenes
    Route::resource('ordenes', OrdenController::class)->parameters(['ordenes' => 'orden']);
    Route::get('/ordenes/datos/mensuales', [OrdenController::class, 'datosMensuales'])->name('ordenes.datos.mensuales');

    // Tareas
    Route::resource('tareas', TareaController::class)->parameters(['tareas' => 'tarea']);
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');

    // Acciones especiales sobre tareas (cronómetro y estado)
    Route::patch('/tareas/{tarea}/estado', [TareaController::class, 'cambiarEstado'])->name('tareas.cambiarEstado');
    Route::patch('/tareas/{tarea}/iniciar-cronometro', [TareaController::class, 'iniciarCronometro'])->name('tareas.iniciar');
    Route::patch('/tareas/{tarea}/finalizar-cronometro', [TareaController::class, 'finalizarCronometro'])->name('tareas.finalizar');
    Route::post('/tareas/{tarea}/marcar-en-curso', [TareaController::class, 'marcarEnCurso'])->name('tareas.marcarEnCurso');
});

require __DIR__ . '/auth.php';
