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
    ComentarioController,
    GraficasController,
    EstadisticasTareasController
};
use App\Http\Middleware\{
    VerificarRol,
    AdminOnly
};

/// Personaliza los verbos por defecto de las rutas resource (por ejemplo, 'create' → 'crear')
Route::resourceVerbs(['create' => 'crear', 'edit' => 'editar']);
Str::singular('ordenes');

/// Redirección automática desde raíz (/) al dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

/// Ruta principal del dashboard con redirección según rol del usuario autenticado
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Redirige a vista de dashboard del empleado
    if ($user?->rol === 'empleado') {
        return redirect()->route('empleado.dashboard');
    }

    // Redirige a dashboard del administrador
    if ($user?->rol === 'admin') {
        return view('dashboard');
    }

    // Si no tiene rol asignado, se le redirige a una vista neutral
    return view('limbo');
})->middleware(['auth', 'verified', VerificarRol::class])->name('dashboard');

/// Rutas protegidas para usuarios autenticados y con rol válido
Route::middleware(['auth', VerificarRol::class])->group(function () {

    /// Dashboard del empleado (comentarios valorados, resumen de actividad)
    Route::get('/dashboard-empleado', [EmpleadoDashboardController::class, 'dashboard'])
        ->name('empleado.dashboard');

    /// Vista de tareas asignadas al empleado actual
    Route::get('/tus-tareas', [EmpleadoDashboardController::class, 'tareas'])
        ->name('empleado.tareas');

    /// Guardar el tiempo invertido en una tarea
    Route::post('/tareas/{id}/guardar-tiempo', [TareaController::class, 'guardarTiempo'])
        ->name('tareas.guardarTiempo');

    /// Finalizar una tarea por parte del empleado
    Route::post('/tareas/{tarea}/finalizar', [TareaController::class, 'finalizar']);

    /// Añadir comentario a una tarea asignada
    Route::post('/tareas/{tarea}/comentarios', [ComentarioController::class, 'store'])
        ->name('comentarios.store');

    /// Eliminar un comentario propio
    Route::delete('/comentarios/{comentario}', [ComentarioController::class, 'destroy'])
        ->name('comentarios.destroy');
});

/// Rutas exclusivas para administradores autenticados
Route::middleware(['auth', VerificarRol::class, AdminOnly::class])->group(function () {

    /// Valorar un comentario realizado por un empleado
    Route::patch('/comentarios/{comentario}/valorar', [ComentarioController::class, 'valorar'])
        ->name('comentarios.valorar');

    /// Ver listado de usuarios pendientes de asignación de rol
    Route::get('/usuarios/pendientes', [UserController::class, 'pendientes'])
        ->name('usuarios.pendientes');

    /// Asignar rol manualmente a un usuario registrado
    Route::post('/usuarios/asignar-rol/{user}', [UserController::class, 'asignarRol'])
        ->name('usuarios.asignarRol');

    /// Perfil del usuario (admin)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /// Gestión de empleados (excepto mostrar y eliminar)
    Route::resource('empleados', EmpleadoController::class)->except(['show', 'destroy']);

    /**
     * Rutas para gestión de faltas y asistencia
     */
    Route::get('/faltas', [FaltasController::class, 'index'])->name('asistencias.index');
    Route::post('/faltas', [FaltasController::class, 'store'])->name('faltas.store');
    Route::get('/faltas/crear', [FaltasController::class, 'crearManual'])->name('faltas.crear.manual');
    Route::post('/faltas/crear', [FaltasController::class, 'guardarManual'])->name('faltas.guardar.manual');
    Route::get('/faltas/graficas/faltas-mensuales/{empleado}', [FaltasController::class, 'faltasAnuales']);

    /**
     * Gráficas de asistencia individual y global
     */
    Route::get('/faltas/grafico/{empleado}', [GraficasController::class, 'grafico'])->name('faltas.grafico');
    Route::get('/faltas/graficas', [GraficasController::class, 'graficasGlobal'])->name('faltas.graficas.global');
    Route::get('/faltas/graficas/datos/{empleado}', [GraficasController::class, 'datosGrafico'])->name('faltas.grafico.datos');

    /**
     * Estadísticas de tareas y órdenes por empleado
     */
    Route::get('/graficas/tareas-por-empleado', [EstadisticasTareasController::class, 'tareasPorEmpleadoMes']);
    Route::get('/graficas/ordenes-por-empleado', [EstadisticasTareasController::class, 'ordenesPorEmpleadoMes']);
    Route::get('/faltas/graficas/tareas-mensuales', [EstadisticasTareasController::class, 'datosGraficoTareasMes'])->name('faltas.grafico.tareas-mensuales');
    Route::get('/faltas/graficas/ordenes-mensuales', [EstadisticasTareasController::class, 'ordenesMensualesPorEmpleado'])->name('faltas.grafico.ordenes-mensuales');

    /**
     * Gestión de órdenes de reparación
     */
    Route::resource('ordenes', OrdenController::class)->parameters(['ordenes' => 'orden']);
    Route::get('/ordenes/datos/mensuales', [OrdenController::class, 'datosMensuales'])->name('ordenes.datos.mensuales');

    /**
     * Gestión de tareas (crear, actualizar, cronómetro, etc.)
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
     * Actualización manual del horario de entrada (por el administrador)
     */
    Route::patch('/registro-entrada/{id}/actualizar-hora', [FaltasController::class, 'actualizarHora'])->name('registroEntrada.actualizarHora');

    /**
     * Integración externa con Holded (ERP o CRM)
     */
    Route::get('/holded/buscar-contacto', [HoldedController::class, 'buscarContacto']);
});

/// Rutas de autenticación por defecto (login, register, etc.)
require __DIR__ . '/auth.php';
