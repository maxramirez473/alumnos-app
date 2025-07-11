<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\NotaController;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
Route::get('/alumnos/create', [AlumnoController::class, 'create'])->name('alumnos.create');
Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::get('/alumnos/{id}/edit', [AlumnoController::class, 'edit'])->name('alumnos.edit');
Route::put('/alumno/edit', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
Route::get('/alumnos/import', [AlumnoController::class, 'showImportForm'])->name('alumnos.import.form');
Route::post('/alumnos/import', [AlumnoController::class, 'import'])->name('alumnos.import');

Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
Route::get('/grupos/create', [GrupoController::class, 'create'])->name('grupos.create');
Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
Route::get('/grupos/{id}/edit', [GrupoController::class, 'edit'])->name('grupos.edit');
Route::put('/grupos/edit', [GrupoController::class, 'update'])->name('grupos.update');
Route::delete('/grupos/{id}', [GrupoController::class, 'destroy'])->name('grupos.destroy');

Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
Route::get('/notas/create', [NotaController::class, 'create'])->name('notas.create');
Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
Route::get('/notas/{id}/edit', [NotaController::class, 'edit'])->name('notas.edit');
Route::put('/notas/edit', [NotaController::class, 'update'])->name('notas.update');
Route::delete('/notas/{id}', [NotaController::class, 'destroy'])->name('notas.destroy');



