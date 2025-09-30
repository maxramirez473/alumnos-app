<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AlumnoController;
use App\Http\Controllers\API\GrupoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ruta para login
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Consultar todos los alumnos
    Route::get('alumnos', [AlumnoController::class, 'index']);
    // Consultar un alumno por ID
    Route::get('alumnos/{id}', [AlumnoController::class, 'show']);
    // Crear un nuevo alumno
    Route::post('alumnos', [AlumnoController::class, 'store']);
    // Actualizar un alumno
    Route::put('alumnos/{id}', [AlumnoController::class, 'update']);
    // Eliminar un alumno
    Route::delete('alumnos/{id}', [AlumnoController::class, 'destroy']);
    
    // CRUD de Grupos
    Route::get('grupos', [GrupoController::class, 'index']);
    Route::get('grupos/{id}', [GrupoController::class, 'show']);
    Route::post('grupos', [GrupoController::class, 'store']);
    Route::put('grupos/{id}', [GrupoController::class, 'update']);
    Route::delete('grupos/{id}', [GrupoController::class, 'destroy']);
    
    // Obtener alumnos de un grupo
    Route::get('grupos/{id}/alumnos', [GrupoController::class, 'alumnos']);
});
