@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Bienvenido a la Aplicación de Alumnos</h2>
    <p>Esta es una aplicación para gestionar alumnos y grupos.</p>
    
    <div class="mt-4">
        <a href="{{ route('alumnos.index') }}" class="btn btn-primary">Ver Alumnos</a>
    </div>
@endsection