<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupo;

class GrupoController extends Controller
{
    // Listar todos los grupos
    public function index()
    {
        $grupos = Grupo::orderBy('nombre', 'asc')->get();
        return response()->json($grupos);
    }

    // Mostrar un grupo por ID
    public function show($id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }
        return response()->json($grupo);
    }

    // Crear un nuevo grupo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:grupos,nombre',
            'numero' => 'nullable|integer|unique:grupos,numero',
        ]);
        
        $grupo = Grupo::create($validated);
        return response()->json($grupo, 201);
    }

    // Actualizar un grupo
    public function update(Request $request, $id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:grupos,nombre,' . $id,
            'numero' => 'nullable|integer|unique:grupos,numero,' . $id,
        ]);
        
        $grupo->update($validated);
        return response()->json($grupo);
    }

    // Eliminar un grupo
    public function destroy($id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }
        
        $grupo->delete();
        return response()->json(null, 204);
    }

    /**
     * Get all students from a specific group
     */
    public function alumnos(string $id)
    {
        $grupo = Grupo::find($id);
        
        if (!$grupo) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }
        
        $alumnos = $grupo->alumnos;
        return response()->json(['data' => $alumnos], 200);
    }
}
