@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h2>Agregar Nota</h2>
    <form action="{{ route('notas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nota" class="form-label">Nota</label>
            <input type="number" name="nota" id="nota" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="concepto_id" class="form-label">Concepto</label>
            <select name="concepto_id" id="concepto_id" class="form-select" required>
                <option value="">Seleccione un concepto</option>
                @foreach($conceptos as $concepto)
                    <option value="{{ $concepto->id }}">{{ $concepto->nombre }} - {{ $concepto->descripcion}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="alumno_id" class="form-label">Alumno</label>
            <select name="alumno_id" id="alumno_id" class="form-select" required>
                <option value="">Seleccione un alumno</option>
                @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->id }}">{{ $alumno->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
        <button type="button" class="btn btn-warning">
            <a href="{{ route('notas.index') }}" class="text-white text-decoration-none">Cancelar</a>
        </button>
    </form>
</div>
@endsection