<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AlumnoController;

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
});
