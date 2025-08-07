@extends('layouts.app')

@section('title', 'Detalles de Entrega')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-clipboard-check text-primary me-2"></i>
                        {{ $entrega->titulo }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('entregas.index') }}">Entregas</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $entrega->titulo }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('entregas.edit', $entrega) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </a>
                    <a href="{{ route('entregas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Información de la Entrega -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Información de la Entrega
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Título:</label>
                                <p class="mb-0"><strong>{{ $entrega->titulo }}</strong></p>
                            </div>

                            @if($entrega->descripcion)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Descripción:</label>
                                    <p class="mb-0">{{ $entrega->descripcion }}</p>
                                </div>
                            @endif

                            @if($entrega->fecha_limite)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Fecha Límite:</label>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($entrega->fecha_limite)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label text-muted">Fecha de Creación:</label>
                                <p class="mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $entrega->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="mb-0">
                                <label class="form-label text-muted">Grupos Asignados:</label>
                                <p class="mb-0">
                                    <span class="badge bg-primary">{{ $entrega->grupos->count() }} grupo(s)</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grupos Asignados -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2"></i>
                                Grupos Asignados a esta Entrega
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($entrega->grupos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Grupo</th>
                                                <th>Miembros</th>
                                                <th>Estado Entrega</th>
                                                <th>Calificación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entrega->grupos as $grupo)
                                                <tr>
                                                    <td>
                                                        <strong class="text-primary">{{ $grupo->nombre }}</strong>
                                                        @if(isset($grupo->pivot->created_at))
                                                            <br>
                                                            <small class="text-muted">
                                                                Asignado: {{ \Carbon\Carbon::parse($grupo->pivot->created_at)->format('d/m/Y') }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($grupo->alumnos->count() > 0)
                                                            <div>
                                                                @foreach($grupo->alumnos->take(3) as $alumno)
                                                                    <span class="badge bg-light text-dark me-1">
                                                                        {{ $alumno->nombre }}
                                                                    </span>
                                                                @endforeach
                                                                @if($grupo->alumnos->count() > 3)
                                                                    <small class="text-muted">
                                                                        +{{ $grupo->alumnos->count() - 3 }} más
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <small class="text-muted">
                                                                Total: {{ $grupo->alumnos->count() }} miembro(s)
                                                            </small>
                                                        @else
                                                            <span class="text-muted">Sin miembros</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($grupo->pivot->fecha_entrega))
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check me-1"></i>
                                                                Entregado
                                                            </span>
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($grupo->pivot->fecha_entrega)->format('d/m/Y H:i') }}
                                                            </small>
                                                        @else
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock me-1"></i>
                                                                Pendiente
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($grupo->pivot->calificacion))
                                                            <span class="badge bg-primary fs-6">
                                                                {{ $grupo->pivot->calificacion }}/10
                                                            </span>
                                                            @if(isset($grupo->pivot->fecha_calificacion))
                                                                <br>
                                                                <small class="text-muted">
                                                                    {{ \Carbon\Carbon::parse($grupo->pivot->fecha_calificacion)->format('d/m/Y') }}
                                                                </small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Sin calificar</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('grupos.show', $grupo) }}" 
                                                               class="btn btn-outline-primary btn-sm"
                                                               title="Ver grupo">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('entregas.editar_grupo', [$entrega, $grupo]) }}" 
                                                               class="btn btn-outline-warning btn-sm"
                                                               title="Editar entrega del grupo">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay grupos asignados</h5>
                                    <p class="text-muted">Esta entrega no tiene grupos asignados aún.</p>
                                    <a href="{{ route('entregas.edit', $entrega) }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>
                                        Asignar Grupos
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
