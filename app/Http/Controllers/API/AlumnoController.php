<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumno;

class AlumnoController extends Controller
{
    // Listar todos los alumnos
    public function index()
    {
        $alumnos = Alumno::orderBy('legajo', 'asc')->get();
        return response()->json($alumnos);
    }

    // Mostrar un alumno por ID
    public function show($id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
        return response()->json($alumno);
    }

    // Crear un nuevo alumno
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnos,email',
            'grupo_id' => 'nullable|exists:grupos,id',
        ]);
        $alumno = Alumno::create($validated);
        return response()->json($alumno, 201);
    }

    // Actualizar un alumno
    public function update(Request $request, $id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnos,email,' . $id,
            'grupo_id' => 'nullable|exists:grupos,id',
        ]);
        $alumno->update($validated);
        return response()->json($alumno);
    }

    // Eliminar un alumno
    public function destroy($id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return response()->json(['error' => 'Alumno no encontrado'], 404);
        }
        $alumno->delete();
        return response()->json(['message' => 'Alumno eliminado correctamente']);
    }
}
