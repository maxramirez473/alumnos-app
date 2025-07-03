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
    
    <h2>Agregar Grupo</h2>
    <form action="{{ route('grupos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Grupo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Número</label>
            <input type="number" class="form-control" id="numero" name="numero" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection