<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Importamos los controladores del Admin
use App\Http\Controllers\Admin\CursoController as AdminCursoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Middleware\SoloAlumnos;

// Importamos el controlador del Profesor
use App\Http\Controllers\Profesor\CursoController as ProfesorCursoController;
use App\Http\Controllers\Profesor\EvaluacionController as ProfesorEvaluacionController;

// Importamos el controlador del Alumno
use App\Http\Controllers\Alumno\DashboardController as AlumnoDashboardController;
use App\Http\Controllers\Alumno\ExplorarController;

// Ruta principal (Bienvenida)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ----------------------------------------------------------------------
// 1. LA RUTA SEMÁFORO (CORREGIDA)
// ----------------------------------------------------------------------
Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();

    // AHORA SÍ USA TU COLUMNA 'rol'
    if ($user->rol === 'admin') {
        return redirect()->route('admin.cursos.index');
    }
    if ($user->rol === 'profesor') {
        return redirect()->route('profesor.cursos.index');
    }
    
    // Si no es admin ni profesor, va a alumno
    return redirect()->route('alumno.dashboard');

})->middleware(['auth'])->name('dashboard');


// ----------------------------------------------------------------------
// TODAS LAS RUTAS PROTEGIDAS (Solo usuarios logueados)
// ----------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    // RUTA DEL PERFIL DE ADMIN 
    Route::get('/admin/perfil', function () {
        return Inertia::render('Admin/Perfil', [
            'estadisticas' => [
                'cursos_completados' => 12,
                'certificados' => 8,
                'promedio' => 95
            ]
        ]);
    })->name('admin.perfil');

    // ------------------------------------------------------------------
    // ZONA ADMINISTRADOR
    // ------------------------------------------------------------------
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Cursos (Corregido: quitamos el "admin." duplicado)
        Route::get('/cursos', [AdminCursoController::class, 'index'])->name('cursos.index');
        Route::get('/cursos/crear', [AdminCursoController::class, 'create'])->name('cursos.create');
        Route::post('/cursos', [AdminCursoController::class, 'store'])->name('cursos.store');
        Route::get('/cursos/{id}/editar', [AdminCursoController::class, 'edit'])->name('cursos.edit');
        Route::post('/cursos/{id}/actualizar', [AdminCursoController::class, 'update'])->name('cursos.update');
        Route::delete('/cursos/{id}/eliminar', [AdminCursoController::class, 'destroy'])->name('cursos.destroy');
        Route::delete('/archivos/{id}', [AdminCursoController::class, 'eliminarArchivo'])->name('archivos.destroy');

        // Alumnos
        Route::get('/alumnos', [UsuarioController::class, 'alumnos'])->name('alumnos.index');
        Route::get('/alumnos/{id}', [UsuarioController::class, 'verAlumno'])->name('alumnos.show');
        
        // Profesores
        Route::get('/profesores', [UsuarioController::class, 'profesores'])->name('profesores.index');
        Route::get('/profesores/{id}', [UsuarioController::class, 'verProfesor'])->name('profesores.show');
    });

    // ------------------------------------------------------------------
    // ZONA PROFESORES
    // ------------------------------------------------------------------
    Route::prefix('profesor')->name('profesor.')->group(function () {
        Route::get('/mis-cursos', [ProfesorCursoController::class, 'index'])->name('cursos.index');
        Route::get('/cursos/crear', [ProfesorCursoController::class, 'create'])->name('cursos.create');
        Route::post('/cursos/guardar', [ProfesorCursoController::class, 'store'])->name('cursos.store');
        Route::get('/cursos/{id}/editar', [ProfesorCursoController::class, 'edit'])->name('cursos.edit');
        Route::post('/cursos/{id}/actualizar', [ProfesorCursoController::class, 'update'])->name('cursos.update');
        Route::delete('/cursos/{id}/eliminar', [ProfesorCursoController::class, 'destroy'])->name('cursos.destroy');
        Route::delete('/archivos/{id}', [ProfesorCursoController::class, 'eliminarArchivo'])->name('archivos.destroy');
    });

    // ------------------------------------------------------------------
    // ZONA ALUMNOS 
    // ------------------------------------------------------------------
    Route::prefix('alumno')->name('alumno.')->group(function () {
        Route::get('/dashboard', [AlumnoDashboardController::class, 'index'])
            ->middleware([SoloAlumnos::class]) // Protegido contra admins/profesores
            ->name('dashboard');

        Route::get('/explorar', [ExplorarController::class, 'index'])
        ->middleware([SoloAlumnos::class])
        ->name('explorar');
        
        Route::post('/inscribir/{curso}', [ExplorarController::class, 'inscribir'])
        ->middleware([SoloAlumnos::class])
        ->name('inscribir');

        Route::get('/curso/{curso}', [SalaClasesController::class, 'show'])->name('cursos.show');
        Route::post('/modulo/{modulo}/completar', [SalaClasesController::class, 'completarModulo'])->name('modulos.completar');
    });

    // ------------------------------------------------------------------
    // RUTAS POR DEFECTO DE LARAVEL BREEZE (Perfil)
    // ------------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Carga las rutas de login y registro
require __DIR__.'/auth.php';