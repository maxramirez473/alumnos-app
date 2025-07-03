@extends('layouts.app')

@section('content')

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <h1>Listado de Grupos</h1>
    <a href="{{ route('grupos.create') }}" class="btn btn-primary mb-3">Agregar Grupo</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número</th>
                <th>Nombre del Grupo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grupos as $grupo)
            <tr>
                <td>{{ $grupo->numero}}</td>
                <td>{{ $grupo->nombre }}</td>
                <td>
                    <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este grupo?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection