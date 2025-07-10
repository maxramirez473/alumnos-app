<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Alumno;
use App\Models\Concepto;

class NotaController extends Controller
{
    public function index()
    {
        // Aquí puedes implementar la lógica para listar las notas
        // Por ejemplo, obtener todas las notas y pasarlas a una vista
        $notas = Nota::with(['alumno', 'concepto'])->where('estado','Activo')->get();
        //dd($notas);
        return view('notas.index', compact('notas'));
    }

    public function create()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de creación de notas
        $alumnos = Alumno::all();
        $conceptos = Concepto::all();
        return view('notas.create', compact('alumnos', 'conceptos'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Aquí puedes implementar la lógica para almacenar una nueva nota
        $request->validate([
            'nota' => 'required|numeric',
            'alumno_id' => 'required|exists:alumnos,id',
            'concepto_id' => 'required|exists:conceptos,id',
        ]);

        Nota::create($request->all());
        return redirect()->route('notas.index')->with('success', 'Nota creada exitosamente.');
    }

    public function edit($id)
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de edición de una nota
        $nota = Nota::findOrFail($id);
        $alumnos = Alumno::all();
        $conceptos = Concepto::all();
        return view('notas.edit', compact('nota', 'alumnos', 'conceptos'));
    }

    public function update(Request $request)
    {
        //dd($request->all());
        // Aquí puedes implementar la lógica para actualizar una nota existente
        $request->validate([
            'nota' => 'required|numeric',
            'alumno_id' => 'required|exists:alumnos,id',
            'concepto_id' => 'required|exists:conceptos,id',
        ]);

        $nota = Nota::findOrFail($request->id);
        $nota->update($request->all());
        return redirect()->route('notas.index')->with('success', 'Nota actualizada exitosamente.');
    }

    public function destroy($id)
    {
        // Aquí puedes implementar la lógica para eliminar una nota
        $nota = Nota::findOrFail($id);
        $nota->estado = 'Inactivo'; // Cambiar el estado a Inactivo en lugar de eliminar
        $nota->save();
        //dd($nota);
        //$nota->delete();
        return redirect()->route('notas.index')->with('success', 'Nota eliminada exitosamente.');
    }
}
