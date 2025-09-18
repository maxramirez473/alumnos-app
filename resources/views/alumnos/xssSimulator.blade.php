@extends('layouts.app')

@section('title', 'Simulador de XSS')

@section('content')
<div class="container mt-4">
    <h1>Simulador de Cross-Site Scripting (XSS)</h1>
    <form method="POST" action="{{ url('alumnos/xssSimulator') }}" class="mb-3">
        @csrf
        <div class="mb-3">
            <label for="comentario" class="form-label">Comentario:</label>
            <input type="text" name="comentario" id="comentario" class="form-control" required>
            <small class="form-text text-muted">Para simular XSS, ingresa: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></small>
        </div>
        <button type="submit" class="btn btn-warning">Enviar</button>
    </form>
    <hr>
    <h2>Comentario enviado</h2>
    @if($comentario)
        <div class="alert alert-danger">
            {!! $comentario !!} <!-- Vulnerable: muestra el comentario sin escape -->
        </div>
    @else
        <p>No se ha enviado ningún comentario.</p>
    @endif
</div>
@endsection
