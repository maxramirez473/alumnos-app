@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Grupo</h2>
    <form action="{{ route('grupos.update', $grupo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $grupo->id }}">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Grupo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $grupo->nombre) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection