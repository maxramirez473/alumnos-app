<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::orderBy('numero', 'asc')->get(); // Obtener todos los grupos ordenados por nombre
        return view('grupos.index',compact('grupos'));
    }

    public function create()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de creación de grupo
        return view('grupos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|unique:grupos,numero', // Validar que el número del grupo sea único
            'nombre' => 'required|string|max:255', // Validar que el nombre del grupo no esté vacío
        ]);

        $grupo = Grupo::create([
            'numero' => $request->numero,
            'nombre' => $request->nombre,
        ]);

        // Aquí puedes implementar la lógica para almacenar un nuevo grupo
        return redirect()->route('grupos.index')->with('success', 'Grupo '.$grupo->nombre.' creado exitosamente.');
    }

    public function edit($id)
    {   
        $grupo= Grupo::findOrFail($id); // Obtener el grupo por ID
        if (!$grupo) {
            return redirect()->route('grupos.index')->with('error', 'Grupo no encontrado.');
        }
        // Aquí puedes implementar la lógica para mostrar el formulario de edición de un grupo
        return view('grupos.edit', compact('grupo'));
    }

    public function update(Request $request)
    {
        $grupo = Grupo::findOrFail($request->id); // Obtener el grupo por ID
        if (!$grupo) {
            return redirect()->route('grupos.index')->with('error', 'Grupo no encontrado.');
        }

        // Aquí puedes implementar la lógica para actualizar los datos del grupo
        // Por ejemplo, validar los datos y guardar los cambios

        $grupo->update($request->all()); // Actualizar el grupo con los datos del formulario

        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        
        // Verificar si el grupo tiene alumnos asignados
        $alumnosCount = $grupo->alumnos()->count();
        
        if ($alumnosCount > 0) {
            return redirect()->route('grupos.index')
                ->with('error', "No se puede eliminar el grupo '{$grupo->nombre}' porque tiene {$alumnosCount} alumno(s) asignado(s). Primero reasigne o elimine los alumnos.");
        }
        
        // Si no hay alumnos, proceder con la eliminación
        $nombreGrupo = $grupo->nombre;
        $grupo->delete();
        
        return redirect()->route('grupos.index')
            ->with('success', "Grupo '{$nombreGrupo}' eliminado exitosamente.");
    }
}
