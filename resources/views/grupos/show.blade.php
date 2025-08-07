@extends('layouts.app')

@section('title', 'Detalle del Grupo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Grupo {{ $grupo->numero }}: {{ $grupo->nombre }}
                    </h4>
                    <div>
                        <a href="{{ route('grupos.edit', $grupo) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información básica del grupo -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        Información del Grupo
                                    </h6>
                                    <p class="mb-1"><strong>Número:</strong> {{ $grupo->numero }}</p>
                                    <p class="mb-1"><strong>Nombre:</strong> {{ $grupo->nombre }}</p>
                                    @if($grupo->created_at)
                                        <p class="mb-1"><strong>Creado:</strong> {{ $grupo->created_at->format('d/m/Y H:i') }}</p>
                                    @else
                                        <p class="mb-1"><strong>Creado:</strong> <span class="text-muted">No disponible</span></p>
                                    @endif
                                    @if($grupo->updated_at)
                                        <p class="mb-0"><strong>Actualizado:</strong> {{ $grupo->updated_at->format('d/m/Y H:i') }}</p>
                                    @else
                                        <p class="mb-0"><strong>Actualizado:</strong> <span class="text-muted">No disponible</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-chart-bar text-success me-2"></i>
                                        Estadísticas
                                    </h6>
                                    <p class="mb-1"><strong>Total de alumnos:</strong> {{ $grupo->alumnos->count() }}</p>
                                    <p class="mb-0"><strong>Entregas asignadas:</strong> {{ $grupo->entregas->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alumnos del grupo -->
                    <div class="mb-4">
                        <h5>
                            <i class="fas fa-user-graduate text-primary me-2"></i>
                            Alumnos del Grupo ({{ $grupo->alumnos->count() }})
                        </h5>
                        @if($grupo->alumnos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No. Control</th>
                                            <th>Nombre</th>
                                            <th>Apellidos</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Notas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupo->alumnos as $alumno)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $alumno->numero_control }}</span>
                                                </td>
                                                <td>{{ $alumno->nombre }}</td>
                                                <td>{{ $alumno->apellidos }}</td>
                                                <td>
                                                    @if($alumno->email)
                                                        <a href="mailto:{{ $alumno->email }}" class="text-decoration-none">
                                                            <i class="fas fa-envelope me-1"></i>
                                                            {{ $alumno->email }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No disponible</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($alumno->telefono)
                                                        <i class="fas fa-phone me-1"></i>
                                                        {{ $alumno->telefono }}
                                                    @else
                                                        <span class="text-muted">No disponible</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $alumno->notas->count() }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Este grupo no tiene alumnos asignados aún.
                                <a href="{{ route('alumnos.index') }}" class="alert-link">Asignar alumnos</a>
                            </div>
                        @endif
                    </div>

                    <!-- Entregas asignadas -->
                    <div class="mb-4">
                        <h5>
                            <i class="fas fa-tasks text-warning me-2"></i>
                            Entregas Asignadas ({{ $grupo->entregas->count() }})
                        </h5>
                        @if($grupo->entregas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Título</th>
                                            <th>Descripción</th>
                                            <th>Fecha Límite</th>
                                            <th>Estado Entrega</th>
                                            <th>Calificación</th>
                                            <th>Creada</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grupo->entregas as $entrega)
                                            <tr>
                                                <td>
                                                    <strong>{{ $entrega->titulo }}</strong>
                                                </td>
                                                <td>
                                                    @if($entrega->descripcion)
                                                        {{ Str::limit($entrega->descripcion, 50) }}
                                                    @else
                                                        <span class="text-muted">Sin descripción</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entrega->fecha_limite)
                                                        @php
                                                            $fechaLimite = \Carbon\Carbon::parse($entrega->fecha_limite);
                                                            $ahora = \Carbon\Carbon::now();
                                                        @endphp
                                                        <span class="badge {{ $fechaLimite->isPast() ? 'bg-danger' : ($fechaLimite->diffInDays($ahora) <= 3 ? 'bg-warning' : 'bg-success') }}">
                                                            {{ $fechaLimite->format('d/m/Y H:i') }}
                                                        </span>
                                                        <small class="d-block text-muted">
                                                            {{ $fechaLimite->diffForHumans() }}
                                                        </small>
                                                    @else
                                                        <span class="text-muted">Sin fecha límite</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entrega->pivot->fecha_entrega)
                                                        <span class="badge bg-success">
                                                            {{ \Carbon\Carbon::parse($entrega->pivot->fecha_entrega)->format('d/m/Y H:i') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">Pendiente</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entrega->pivot->calificacion)
                                                        <span class="badge bg-info">{{ $entrega->pivot->calificacion }}</span>
                                                    @else
                                                        <span class="text-muted">Sin calificar</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entrega->created_at)
                                                        <small class="text-muted">
                                                            {{ $entrega->created_at->format('d/m/Y') }}
                                                        </small>
                                                    @else
                                                        <small class="text-muted">No disponible</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('entregas.show', $entrega) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Este grupo no tiene entregas asignadas aún.
                                <a href="{{ route('entregas.create') }}" class="alert-link">Crear una entrega</a>
                            </div>
                        @endif
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver a Grupos
                        </a>
                        <div>
                            <a href="{{ route('grupos.edit', $grupo) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i>
                                Editar Grupo
                            </a>
                            <form action="{{ route('grupos.destroy', $grupo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este grupo? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
