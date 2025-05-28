<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    EmpleadoController,
    FaltasController,
    OrdenController,
    TareaController,
    UserController,
    EmpleadoDashboardController,
    HoldedController,
    ComentarioController
};
use App\Http\Middleware\{
    VerificarRol,
    AdminOnly
};

/**
 * Configuración inicial de verbos para rutas
 */
Route::resourceVerbs(['create' => 'crear', 'edit' => 'editar']);
Str::singular('ordenes');

/**
 * Redirección de la raíz al dashboard
 */
Route::get('/', fn() => redirect()->route('dashboard'));

/**
 * Ruta principal del dashboard con verificación de rol
 */
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user?->rol === 'empleado') {
        return redirect()->route('empleado.dashboard');
    }

    if ($user?->rol === 'admin') {
        return view('dashboard');
    }

    // Usuarios sin rol definido van al limbo
    return view('limbo');
})->middleware(['auth', 'verified', VerificarRol::class])->name('dashboard');

/**
 * Grupo de rutas accesibles solo para usuarios autenticados con rol verificado
 */
Route::middleware(['auth', VerificarRol::class])->group(function () {

    /**
     * Dashboard para empleados
     */
    Route::get('/dashboard-empleado', [EmpleadoDashboardController::class, 'dashboard'])
        ->name('empleado.dashboard');

    /**
     * Vista de tareas asignadas para empleados
     */
    Route::get('/tus-tareas', [EmpleadoDashboardController::class, 'tareas'])
        ->name('empleado.tareas');

    /**
     * Gestión del cronómetro de tareas desde vista de empleados
     */
    Route::post('/tareas/{id}/guardar-tiempo', [TareaController::class, 'guardarTiempo'])
        ->name('tareas.guardarTiempo');

    Route::post('/tareas/{tarea}/finalizar', [TareaController::class, 'finalizar']);

    /**
     * Comentarios de tareas
     */
    Route::post('/tareas/{tarea}/comentarios', [ComentarioController::class, 'store'])
        ->name('comentarios.store');

    Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])
        ->name('comentarios.destroy');
});

/**
 * Grupo de rutas exclusivas para administradores
 */
Route::middleware(['auth', VerificarRol::class, AdminOnly::class])->group(function () {

    /**
     * Valoración de comentarios por parte del administrador
     */
    Route::patch('/comentarios/{comentario}/valorar', [ComentarioController::class, 'valorar'])
        ->name('comentarios.valorar');

    /**
     * Gestión de usuarios pendientes de rol
     */
    Route::get('/usuarios/pendientes', [UserController::class, 'pendientes'])
        ->name('usuarios.pendientes');

    Route::post('/usuarios/asignar-rol/{user}', [UserController::class, 'asignarRol'])
        ->name('usuarios.asignarRol');

    /**
     * Perfil de usuario
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Empleados (excepto show y destroy)
     */
    Route::resource('empleados', EmpleadoController::class)->except(['show', 'destroy']);

    /**
     * Faltas y asistencia
     */
    Route::get('/faltas', [FaltasController::class, 'index'])->name('asistencias.index');
    Route::post('/faltas', [FaltasController::class, 'store'])->name('faltas.store');
    Route::get('/faltas/grafico/{empleado}', [FaltasController::class, 'grafico'])->name('faltas.grafico');
    Route::get('/faltas/graficas', [FaltasController::class, 'graficasGlobal'])->name('faltas.graficas.global');
    Route::get('/faltas/graficas/datos/{empleado}', [FaltasController::class, 'datosGrafico'])->name('faltas.grafico.datos');
    Route::get('/faltas/graficas/faltas-mensuales/{empleado}', [FaltasController::class, 'faltasAnuales']);
    Route::get('/faltas/crear', [FaltasController::class, 'crearManual'])->name('faltas.crear.manual');
    Route::post('/faltas/crear', [FaltasController::class, 'guardarManual'])->name('faltas.guardar.manual');

    /**
     * Gráficas de tareas y órdenes
     */
    Route::get('/graficas/tareas-por-empleado', [FaltasController::class, 'tareasPorEmpleadoMes']);
    Route::get('/graficas/ordenes-por-empleado', [FaltasController::class, 'ordenesPorEmpleadoMes']);
    Route::get('/faltas/graficas/tareas-mensuales', [FaltasController::class, 'datosGraficoTareasMes'])->name('faltas.grafico.tareas-mensuales');
    Route::get('/faltas/graficas/ordenes-mensuales', [FaltasController::class, 'ordenesMensualesPorEmpleado'])->name('faltas.grafico.ordenes-mensuales');

    /**
     * Órdenes de reparación
     */
    Route::resource('ordenes', OrdenController::class)->parameters(['ordenes' => 'orden']);
    Route::get('/ordenes/datos/mensuales', [OrdenController::class, 'datosMensuales'])->name('ordenes.datos.mensuales');

    /**
     * Tareas
     */
    Route::resource('tareas', TareaController::class)->parameters(['tareas' => 'tarea']);
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::patch('/tareas/{tarea}/estado', [TareaController::class, 'cambiarEstado'])->name('tareas.cambiarEstado');
    Route::patch('/tareas/{tarea}/iniciar-cronometro', [TareaController::class, 'iniciarCronometro'])->name('tareas.iniciar');
    Route::patch('/tareas/{tarea}/finalizar-cronometro', [TareaController::class, 'finalizarCronometro'])->name('tareas.finalizar');
    Route::post('/tareas/{tarea}/marcar-en-curso', [TareaController::class, 'marcarEnCurso'])->name('tareas.marcarEnCurso');
    Route::post('/tareas/{tarea}/actualizar-tiempo', [TareaController::class, 'actualizarTiempo'])->name('tareas.actualizarTiempo');

    /**
     * Actualización manual del horario de entrada
     */
    Route::patch('/registro-entrada/{id}/actualizar-hora', [FaltasController::class, 'actualizarHora'])->name('registroEntrada.actualizarHora');

    /**
     * Integración con Holded
     */
    Route::get('/holded/buscar-contacto', [HoldedController::class, 'buscarContacto']);
});

/**
 * Rutas de autenticación (login, registro, etc.)
 */
require __DIR__ . '/auth.php';
