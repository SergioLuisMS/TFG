<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FaltasController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Str;

// Forzar singular correcto para el recurso
Route::resourceVerbs([
    'create' => 'crear',
    'edit' => 'editar',
]);

// Esto corrige que Laravel intente usar "ordene" como singular:
Str::singular('ordenes'); // Asegura que use 'orden'
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Empleados
    Route::get('/empleados/crear', [EmpleadoController::class, 'create'])->name('empleados.create');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/empleados/{empleado}/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');
    Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');

    // Faltas
    Route::get('/faltas', [FaltasController::class, 'index'])->name('asistencias.index');
    Route::post('/faltas', [FaltasController::class, 'store'])->name('faltas.store');
    Route::get('/faltas/grafico/{empleado}', [FaltasController::class, 'grafico'])->name('faltas.grafico');
    Route::get('/faltas/graficas', [FaltasController::class, 'graficasGlobal'])->name('faltas.graficas.global');
    Route::get('/faltas/graficas/datos/{empleado}', [FaltasController::class, 'datosGrafico'])->name('faltas.grafico.datos');
    Route::get('/faltas/crear', [FaltasController::class, 'crearManual'])->name('faltas.crear.manual');
    Route::post('/faltas/crear', [FaltasController::class, 'guardarManual'])->name('faltas.guardar.manual');
    Route::get('/faltas/graficas/faltas-mensuales/{empleado}', [FaltasController::class, 'faltasAnuales']);

    // Órdenes - usamos resource pero corrigiendo el parámetro
    Route::resource('ordenes', OrdenController::class)->parameters([
        'ordenes' => 'orden'
    ]);

    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');

    Route::resource('tareas', TareaController::class)->parameters([
        'tareas' => 'tarea'
    ]);

    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');

    Route::get('/graficas/tareas-por-empleado', [FaltasController::class, 'tareasPorEmpleadoMes']);
    Route::get('/graficas/ordenes-por-empleado', [FaltasController::class, 'ordenesPorEmpleadoMes']);

    Route::get('/faltas/graficas/tareas-mensuales', [FaltasController::class, 'datosGraficoTareasMes'])
        ->name('faltas.grafico.tareas-mensuales');


    Route::get('/faltas/graficas/ordenes-mensuales', [FaltasController::class, 'ordenesMensualesPorEmpleado']);
});

require __DIR__ . '/auth.php';
