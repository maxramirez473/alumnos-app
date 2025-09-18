
@extends('layouts.app')

@section('title', 'Simulador de SQL Injection')

@section('content')
<div class="container mt-4">
    <h1>Simulador de SQL Injection</h1>
    <form method="POST" action="{{ url('alumnos/injectionSimulator') }}" class="mb-3">
        @csrf
        <div class="mb-3">
            <label for="legajo" class="form-label">Legajo del alumno:</label>
            <input type="text" name="legajo" id="legajo" class="form-control" required>
                <small class="form-text text-muted">Para simular SQL Injection, ingresa: <code>1 OR 1=1</code></small>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <hr>
    <h2>Resultados</h2>
    @if(count($alumnos) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Legajo</th>
                </tr>
            </thead>
            <tbody>
            @foreach($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->id }}</td>
                    <td>{{ $alumno->nombre }}</td>
                    <td>{{ $alumno->email }}</td>
                    <td>{{ $alumno->legajo }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No se encontraron resultados.</p>
    @endif
</div>
@endsection
