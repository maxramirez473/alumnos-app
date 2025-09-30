<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumno;

class AlumnoController extends Controller
{
    // Listar todos los alumnos (con búsqueda opcional)
    public function index(Request $request)
    {
        $query = Alumno::query();
        
        // Si hay parámetro de búsqueda, filtrar
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('legajo', 'LIKE', "%{$search}%");
        }
        
        $alumnos = $query->orderBy('legajo', 'asc')->get();
        
        // Retornamos en formato que los tests esperan
        return response()->json([
            'data' => $alumnos,
            'meta' => [
                'total' => $alumnos->count()
            ]
        ]);
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
            'legajo' => 'required|integer|unique:alumnos,legajo',
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
            'legajo' => 'required|integer|unique:alumnos,legajo,' . $id,
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
        return response()->json(null, 204);
    }
}
