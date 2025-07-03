<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Imports\AlumnosImport;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos=Alumno::orderBy('legajo', 'asc')->get();
        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnos,email',
            'grupo_id' => 'nullable|exists:grupos,id',
        ]);

        Alumno::create($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado exitosamente.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnos,email,' . $id,
            'grupo_id' => 'nullable|exists:grupos,id',
        ],
        [
            'email.unique' => 'El email ya está en uso por otro alumno.',
            'grupo_id.exists' => 'El grupo seleccionado no existe.',
        ]);

        $alumno = Alumno::findOrFail($id);
        $alumno->update($request->all());

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado exitosamente.');
    }   

    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        $grupos = Grupo::all(); // Obtener todos los grupos para el formulario
        return view('alumnos.edit', compact('alumno','grupos'));
    }

    public function destroy($id)
    {
        $alumno = Alumno::findOrFail($id);
        $alumno->delete();

        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado exitosamente.');
    }

    public function showImportForm()
    {
        // Lógica para mostrar el formulario de importación
        return view('alumnos.import');
    }

    public function import()
    {
        // Lógica para importar alumnos desde un archivo
        if (request()->isMethod('post')) {
            $request = request();
            $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt'
            ]);

            try {
            \Maatwebsite\Excel\Facades\Excel::import(new AlumnosImport, $request->file('file'));
            return redirect()->route('alumnos.index')->with('success', 'Alumnos importados exitosamente.');
            } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
            }
        }
        return view('alumnos.import');
    }

    // Aquí puedes agregar más métodos según sea necesario
}
