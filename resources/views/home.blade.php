@extends('layouts.app')

@section('title', 'Inicio - Alumnos App')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-light p-5 rounded-3 mb-4">
                <h1 class="display-4">Bienvenido a la Aplicación de Alumnos</h1>
                <p class="lead">Sistema de gestión para alumnos, grupos y notas de la comisión s31.</p>
                
                @guest
                    <hr class="my-4">
                    <p>Para acceder a todas las funcionalidades, necesitas iniciar sesión.</p>
                    <div class="d-flex gap-3">
                        <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Iniciar Sesión</a>
                        <a class="btn btn-outline-primary btn-lg" href="{{ route('register') }}">Registrarse</a>
                    </div>
                @else
                    <hr class="my-4">
                    <p>Hola <strong>{{ Auth::user()->name }}</strong>, ¿qué quieres hacer hoy?</p>
                @endguest
            </div>
        </div>
    </div>

    @auth
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Gestionar Alumnos</h5>
                        <a href="{{ route('alumnos.index') }}" class="btn btn-primary">Ver Alumnos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-layer-group fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Gestionar Grupos</h5>
                        <a href="{{ route('grupos.index') }}" class="btn btn-success">Ver Grupos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Gestionar Notas</h5>
                        <a href="{{ route('notas.index') }}" class="btn btn-warning">Ver Notas</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6 class="alert-heading">Funcionalidades Disponibles:</h6>
                    <ul class="mb-0">
                        <li>Importar alumnos desde archivos Excel</li>
                        <li>Gestión completa de grupos y asignaciones</li>
                        <li>Sistema de calificaciones y seguimiento</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    <h6 class="alert-heading">Características del Sistema:</h6>
                    <ul class="mb-0">
                        <li>Gestión completa de alumnos y grupos</li>
                        <li>Sistema de calificaciones</li>
                        <li>Importación de datos desde Excel</li>
                        <li>Interfaz fácil de usar</li>
                    </ul>
                </div>
            </div>
        </div>
    @endauth
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection 