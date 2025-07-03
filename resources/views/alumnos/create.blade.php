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
    <h2>Agregar Alumno</h2>
    <form action="{{ route('alumnos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Alumno/a</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="formato: apellido/s, nombre/s" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo</label>
            <select name="grupo_id" id="grupo_id" class="form-select" required>
                <option value="">Seleccione un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
        <button type="button" class="btn btn-warning">
            <a href="{{ route('alumnos.index') }}" class="text-white text-decoration-none">Cancelar</a>
        </button>
    </form>
</div>
@endsection