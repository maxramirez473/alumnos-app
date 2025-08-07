<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntregaController;

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ruta pública
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/sesion', function(){
    return session()->all(); // Asegúrate de tener una vista auth/session.blade.php
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::get('/alumnos/create', [AlumnoController::class, 'create'])->name('alumnos.create');
    Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::get('/alumnos/{id}/edit', [AlumnoController::class, 'edit'])->name('alumnos.edit');
    Route::put('/alumno/edit', [AlumnoController::class, 'update'])->name('alumnos.update');
    Route::get('/alumnos/import', [AlumnoController::class, 'showImportForm'])->name('alumnos.import.form');
    Route::post('/alumnos/import', [AlumnoController::class, 'import'])->name('alumnos.import');
    
    // Ruta de eliminación de alumnos (solo admin)
    Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy')->middleware('admin');

    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
    Route::get('/grupos/create', [GrupoController::class, 'create'])->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
    
    // Rutas de edición y eliminación de grupos (solo admin)
    Route::get('/grupos/{id}/edit', [GrupoController::class, 'edit'])->name('grupos.edit')->middleware('admin');
    Route::put('/grupos/edit', [GrupoController::class, 'update'])->name('grupos.update')->middleware('admin');
    Route::delete('/grupos/{id}', [GrupoController::class, 'destroy'])->name('grupos.destroy')->middleware('admin');

    // Todas las rutas de notas (solo admin)
    Route::middleware('admin')->group(function () {
        Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
        Route::get('/notas/create', [NotaController::class, 'create'])->name('notas.create');
        Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
        Route::get('/notas/{id}/edit', [NotaController::class, 'edit'])->name('notas.edit');
        Route::put('/notas/edit', [NotaController::class, 'update'])->name('notas.update');
        Route::delete('/notas/{id}', [NotaController::class, 'destroy'])->name('notas.destroy');
    });

    // Todas las rutas de entregas (solo admin)
    Route::middleware('admin')->group(function () {
        Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');
        Route::get('/entregas/create', [EntregaController::class, 'create'])->name('entregas.create');
        Route::post('/entregas', [EntregaController::class, 'store'])->name('entregas.store');
        Route::get('/entregas/{entrega}', [EntregaController::class, 'show'])->name('entregas.show');
        Route::get('/entregas/{entrega}/edit', [EntregaController::class, 'edit'])->name('entregas.edit');
        Route::put('/entregas/{entrega}', [EntregaController::class, 'update'])->name('entregas.update');
        Route::delete('/entregas/{entrega}', [EntregaController::class, 'destroy'])->name('entregas.destroy');
        
        // Rutas para gestión de entregas por grupo
        Route::get('/entregas/{entrega}/grupos/{grupo}/editar', [EntregaController::class, 'editarEntregaGrupo'])->name('entregas.editar_grupo');
        Route::put('/entregas/{entrega}/grupos/{grupo}', [EntregaController::class, 'actualizarEntregaGrupo'])->name('entregas.actualizar_grupo');
    });
});



