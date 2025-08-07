@extends('layouts.app')

@section('title', 'Inicio - Alumnos App')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-gray p-5 rounded-3 mb-4">
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
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Gestionar Alumnos</h5>
                        <p class="card-text text-muted small mb-3">Administra el registro de estudiantes</p>
                        <a href="{{ route('alumnos.index') }}" class="btn btn-primary mt-auto">Ver Alumnos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="fas fa-layer-group fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Gestionar Grupos</h5>
                        <p class="card-text text-muted small mb-3">Organiza a los estudiantes en grupos</p>
                        <a href="{{ route('grupos.index') }}" class="btn btn-success mt-auto">Ver Grupos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Gestionar Notas</h5>
                        <p class="card-text text-muted small mb-3">Sistema de calificaciones</p>
                        <a href="{{ route('notas.index') }}" class="btn btn-warning mt-auto">Ver Notas</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="fas fa-clipboard-check fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Gestionar Entregas</h5>
                        <p class="card-text text-muted small mb-3">Administra entregas de grupos</p>
                        <a href="{{ route('entregas.index') }}" class="btn btn-info mt-auto">Ver Entregas</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección de estadísticas rápidas -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3">Resumen del Sistema</h4>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-users text-primary mb-2"></i>
                        <h5 class="card-title text-primary">Alumnos</h5>
                        <h3 class="mb-0">{{ \App\Models\Alumno::count() ?? 0 }}</h3>
                        <small class="text-muted">Registrados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-layer-group text-success mb-2"></i>
                        <h5 class="card-title text-success">Grupos</h5>
                        <h3 class="mb-0">{{ \App\Models\Grupo::count() ?? 0 }}</h3>
                        <small class="text-muted">Activos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-star text-warning mb-2"></i>
                        <h5 class="card-title text-warning">Notas</h5>
                        <h3 class="mb-0">{{ \App\Models\Nota::count() ?? 0 }}</h3>
                        <small class="text-muted">Calificaciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-check text-info mb-2"></i>
                        <h5 class="card-title text-info">Entregas</h5>
                        <h3 class="mb-0">{{ \App\Models\Entrega::count() ?? 0 }}</h3>
                        <small class="text-muted">Registradas</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3">Acciones Rápidas</h4>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-plus text-primary me-2"></i>Crear Nuevo</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('alumnos.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user-plus me-1"></i>Alumno
                            </a>
                            <a href="{{ route('grupos.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-plus me-1"></i>Grupo
                            </a>
                            <a href="{{ route('notas.create') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-star me-1"></i>Nota
                            </a>
                            <a href="{{ route('entregas.create') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-clipboard-check me-1"></i>Entrega
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-tools text-secondary me-2"></i>Herramientas</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('alumnos.import.form') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-file-import me-1"></i>Importar Alumnos
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm" onclick="exportAllData()">
                                <i class="fas fa-download me-1"></i>Exportar Datos
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#statsModal">
                                <i class="fas fa-chart-bar me-1"></i>Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Funcionalidades del Sistema:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>Gestión completa de alumnos y grupos</li>
                                <li>Sistema de calificaciones y evaluaciones</li>
                                <li>Importación de datos desde archivos Excel</li>
                                <li>Organización de estudiantes en grupos</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li>Administración de entregas por grupos</li>
                                <li>Seguimiento de trabajos y proyectos</li>
                                <li>Reportes y estadísticas detalladas</li>
                                <li>Interfaz moderna y fácil de usar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de estadísticas -->
        <div class="modal fade" id="statsModal" tabindex="-1" aria-labelledby="statsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statsModalLabel">
                            <i class="fas fa-chart-bar me-2"></i>Estadísticas del Sistema
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Rendimiento Académico</h6>
                                <p><strong>Promedio General:</strong> {{ number_format(\App\Models\Nota::avg('nota') ?? 0, 2) }}/10</p>
                                <p><strong>Notas Aprobadas:</strong> {{ \App\Models\Nota::where('nota', '>=', 7)->count() ?? 0 }}</p>
                                <p><strong>Notas Desaprobadas:</strong> {{ \App\Models\Nota::where('nota', '<', 7)->count() ?? 0 }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Distribución</h6>
                                <p><strong>Grupos con Alumnos:</strong> {{ \App\Models\Grupo::has('alumnos')->count() ?? 0 }}</p>
                                <p><strong>Grupos Vacíos:</strong> {{ \App\Models\Grupo::doesntHave('alumnos')->count() ?? 0 }}</p>
                                <p><strong>Total de Entregas:</strong> {{ \App\Models\Entrega::count() ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
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
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .jumbotron {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .jumbotron .btn-primary {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }
    
    .jumbotron .btn-outline-primary {
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .jumbotron .btn-outline-primary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
    }
    
    .card-body i {
        transition: transform 0.3s ease;
    }
    
    .card:hover .card-body i {
        transform: scale(1.1);
    }
    
    .border-primary { border-left: 4px solid #007bff !important; }
    .border-success { border-left: 4px solid #28a745 !important; }
    .border-warning { border-left: 4px solid #ffc107 !important; }
    .border-info { border-left: 4px solid #17a2b8 !important; }
    
    .alert-info {
        background: linear-gradient(45deg, #e3f2fd, #f3e5f5);
        border: none;
        border-left: 4px solid #2196f3;
    }
    
    .bg-light {
        background: linear-gradient(45deg, #f8f9fa, #e9ecef) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function exportAllData() {
        // Implementar función de exportación
        alert('Función de exportación en desarrollo');
    }
    
    // Animaciones y efectos
    document.addEventListener('DOMContentLoaded', function() {
        // Animar estadísticas al cargar
        const statsCards = document.querySelectorAll('.border-primary, .border-success, .border-warning, .border-info');
        statsCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
        
        // Efecto de contador para las estadísticas
        const counters = document.querySelectorAll('.border-primary h3, .border-success h3, .border-warning h3, .border-info h3');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            let current = 0;
            const increment = target / 50;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.floor(current);
                    setTimeout(updateCounter, 30);
                } else {
                    counter.textContent = target;
                }
            };
            
            setTimeout(updateCounter, 500);
        });
        
        // Tooltips para los botones
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection 