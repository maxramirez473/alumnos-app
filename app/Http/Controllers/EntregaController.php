<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entregas = Entrega::with(['grupos.alumnos'])->get();

        return view('entregas.index', compact('entregas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupos = Grupo::with('alumnos')->get();
        return view('entregas.create', compact('grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
            'grupos' => 'required|array|min:1',
            'grupos.*' => 'exists:grupos,id'
        ]);

        $entrega = Entrega::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'fecha_limite' => $validated['fecha_limite'],
        ]);

        // Asociar grupos a la entrega
        $entrega->grupos()->attach($validated['grupos']);

        return redirect()->route('entregas.index')
            ->with('success', 'Entrega creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entrega $entrega)
    {
        $entrega->load(['grupos.alumnos']);
        return view('entregas.show', compact('entrega'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrega $entrega)
    {
        $entrega->load(['grupos']);
        $grupos = Grupo::with('alumnos')->get();
        return view('entregas.edit', compact('entrega', 'grupos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrega $entrega)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date',
            'grupos' => 'required|array|min:1',
            'grupos.*' => 'exists:grupos,id'
        ]);

        $entrega->update([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'fecha_limite' => $validated['fecha_limite'],
        ]);

        // Sincronizar grupos
        $entrega->grupos()->sync($validated['grupos']);

        return redirect()->route('entregas.show', $entrega)
            ->with('success', 'Entrega actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrega $entrega)
    {
        $nombre = $entrega->titulo;
        
        // Desasociar grupos
        $entrega->grupos()->detach();
        
        // Eliminar entrega
        $entrega->delete();

        return redirect()->route('entregas.index')
            ->with('success', "Entrega '{$nombre}' eliminada exitosamente.");
    }

    /**
     * Actualizar estado de entrega para un grupo específico
     */
    public function actualizarEstadoGrupo(Request $request, Entrega $entrega, Grupo $grupo)
    {
        $validated = $request->validate([
            'calificacion' => 'nullable|numeric|min:0|max:10',
            'fecha_entrega' => 'nullable|date',
            'comentarios' => 'nullable|string'
        ]);

        try {
            $entrega->grupos()->updateExistingPivot($grupo->id, [
                'calificacion' => $validated['calificacion'],
                'fecha_entrega' => $validated['fecha_entrega'],
                'comentarios' => $validated['comentarios'],
                'fecha_calificacion' => $validated['calificacion'] ? now() : null,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Información actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información.'
            ], 500);
        }
    }

    /**
     * Mostrar formulario para editar entrega de un grupo específico
     */
    public function editarEntregaGrupo(Entrega $entrega, Grupo $grupo)
    {
        // Verificar que el grupo esté asignado a la entrega y cargar datos del pivot
        $grupoPivot = $entrega->grupos()->where('grupo_id', $grupo->id)->first();
        
        if (!$grupoPivot) {
            return redirect()->route('entregas.show', $entrega)
                ->with('error', 'El grupo no está asignado a esta entrega.');
        }

        // Asignar la información del pivot al grupo
        $grupo->pivot = $grupoPivot->pivot;

        return view('entregas.editar-grupo', compact('entrega', 'grupo'));
    }

    /**
     * Actualizar entrega de un grupo específico
     */
    public function actualizarEntregaGrupo(Request $request, Entrega $entrega, Grupo $grupo)
    {
        $validated = $request->validate([
            'calificacion' => 'nullable|numeric|min:0|max:10',
            'fecha_entrega' => 'nullable|date',
            'comentarios' => 'nullable|string'
        ]);

        try {
            $entrega->grupos()->updateExistingPivot($grupo->id, [
                'calificacion' => $validated['calificacion'],
                'fecha_entrega' => $validated['fecha_entrega'],
                'comentarios' => $validated['comentarios'],
                'fecha_calificacion' => $validated['calificacion'] ? now() : null,
                'updated_at' => now()
            ]);

            return redirect()->route('entregas.show', $entrega)
                ->with('success', "Entrega del grupo '{$grupo->nombre}' actualizada exitosamente.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la entrega del grupo.')
                ->withInput();
        }
    }
}
