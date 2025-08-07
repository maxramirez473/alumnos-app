@extends('layouts.app')

@section('title', 'Editar Entrega del Grupo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Editar Entrega del Grupo
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Información contextual -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-clipboard-check text-primary me-2"></i>
                                        Entrega
                                    </h6>
                                    <p class="mb-1"><strong>Título:</strong> {{ $entrega->titulo }}</p>
                                    @if($entrega->fecha_limite)
                                        <p class="mb-0">
                                            <strong>Fecha Límite:</strong> 
                                            {{ \Carbon\Carbon::parse($entrega->fecha_limite)->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-users text-success me-2"></i>
                                        Grupo
                                    </h6>
                                    <p class="mb-1"><strong>Nombre:</strong> {{ $grupo->nombre }}</p>
                                    <p class="mb-0"><strong>Miembros:</strong> {{ $grupo->alumnos->count() }} alumno(s)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Por favor, corrige los siguientes errores:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('entregas.actualizar_grupo', [$entrega, $grupo]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_entrega" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Fecha de Entrega
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('fecha_entrega') is-invalid @enderror" 
                                           id="fecha_entrega" 
                                           name="fecha_entrega" 
                                           value="{{ old('fecha_entrega', ($grupo->pivot && $grupo->pivot->fecha_entrega) ? \Carbon\Carbon::parse($grupo->pivot->fecha_entrega)->format('Y-m-d\TH:i') : '') }}">
                                    <small class="text-muted">Dejar vacío si aún no se ha entregado</small>
                                    @error('fecha_entrega')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="calificacion" class="form-label">
                                        <i class="fas fa-star me-1"></i>
                                        Calificación (0-10)
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('calificacion') is-invalid @enderror" 
                                           id="calificacion" 
                                           name="calificacion" 
                                           min="0" 
                                           max="10" 
                                           step="0.1"
                                           value="{{ old('calificacion', ($grupo->pivot && $grupo->pivot->calificacion) ? $grupo->pivot->calificacion : '') }}"
                                           placeholder="Ej: 8.5">
                                    @error('calificacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="comentarios" class="form-label">
                                        <i class="fas fa-comment me-1"></i>
                                        Comentarios
                                    </label>
                                    <textarea class="form-control @error('comentarios') is-invalid @enderror" 
                                              id="comentarios" 
                                              name="comentarios" 
                                              rows="4"
                                              placeholder="Comentarios sobre la entrega...">{{ old('comentarios', ($grupo->pivot && $grupo->pivot->comentarios) ? $grupo->pivot->comentarios : '') }}</textarea>
                                    @error('comentarios')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Estado actual -->
                        @if($grupo->pivot && ($grupo->pivot->fecha_entrega || $grupo->pivot->calificacion || $grupo->pivot->comentarios))
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Estado Actual</h6>
                                <div class="row">
                                    @if($grupo->pivot->fecha_entrega)
                                        <div class="col-md-6">
                                            <small><strong>Fecha de Entrega:</strong> {{ \Carbon\Carbon::parse($grupo->pivot->fecha_entrega)->format('d/m/Y H:i') }}</small>
                                        </div>
                                    @endif
                                    @if($grupo->pivot->calificacion)
                                        <div class="col-md-6">
                                            <small><strong>Calificación:</strong> {{ $grupo->pivot->calificacion }}/10</small>
                                        </div>
                                    @endif
                                    @if($grupo->pivot->fecha_calificacion)
                                        <div class="col-md-12 mt-2">
                                            <small><strong>Fecha de Calificación:</strong> {{ \Carbon\Carbon::parse($grupo->pivot->fecha_calificacion)->format('d/m/Y H:i') }}</small>
                                        </div>
                                    @endif
                                    @if($grupo->pivot->comentarios)
                                        <div class="col-md-12 mt-2">
                                            <small><strong>Comentarios Actuales:</strong> {{ $grupo->pivot->comentarios }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('entregas.show', $entrega) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver a la Entrega
                            </a>
                            <div>
                                <a href="{{ route('grupos.show', $grupo) }}" class="btn btn-outline-info me-2">
                                    <i class="fas fa-users me-1"></i>
                                    Ver Grupo
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>
                                    Actualizar Entrega
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
