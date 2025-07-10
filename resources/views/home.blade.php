@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Bienvenido a la Aplicación de Alumnos</h2>
    <p>Esta es una aplicación para gestionar alumnos y grupos.</p>
    
    <div class="mt-5 d-flex justify-between">
        <div class="">
            <a href="{{ route('alumnos.index') }}" class="btn btn-primary">Ver Alumnos</a>
        </div>

        <div class="ms-2">
            <a href="{{ route('grupos.index') }}" class="btn btn-success">Ver Grupos</a>
        </div>

        <div class="ms-2">
            <a href="{{ route('notas.index') }}" class="btn btn-warning">Ver Notas</a>
        </div>
    </div>
@endsection