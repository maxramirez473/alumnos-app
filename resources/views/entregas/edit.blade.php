@extends('layouts.app')

@section('title', 'Editar Entrega')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Editar Entrega: {{ $entrega->titulo }}
                    </h4>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('entregas.update', $entrega) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título de la Entrega <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('titulo') is-invalid @enderror" 
                                           id="titulo" 
                                           name="titulo" 
                                           value="{{ old('titulo', $entrega->titulo) }}" 
                                           maxlength="50"
                                           required>
                                    @error('titulo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="3"
                                              placeholder="Descripción de la entrega...">{{ old('descripcion', $entrega->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_limite" class="form-label">Fecha Límite</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('fecha_limite') is-invalid @enderror" 
                                           id="fecha_limite" 
                                           name="fecha_limite" 
                                           value="{{ old('fecha_limite', $entrega->fecha_limite ? $entrega->fecha_limite->format('Y-m-d\TH:i') : '') }}">
                                    @error('fecha_limite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="grupos" class="form-label">Grupos Asignados <span class="text-danger">*</span></label>
                                    <div class="card">
                                        <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                            @if($grupos->count() > 0)
                                                <div class="row">
                                                    @foreach($grupos as $grupo)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       value="{{ $grupo->id }}" 
                                                                       id="grupo_{{ $grupo->id }}"
                                                                       name="grupos[]"
                                                                       {{ in_array($grupo->id, old('grupos', $entrega->grupos->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grupo_{{ $grupo->id }}">
                                                                    <strong>{{ $grupo->nombre }}</strong>
                                                                    @if($grupo->alumnos->count() > 0)
                                                                        <small class="text-muted d-block">
                                                                            <i class="fas fa-users me-1"></i>
                                                                            {{ $grupo->alumnos->count() }} miembro(s):
                                                                            {{ $grupo->alumnos->pluck('nombre')->take(2)->implode(', ') }}
                                                                            @if($grupo->alumnos->count() > 2)
                                                                                y {{ $grupo->alumnos->count() - 2 }} más
                                                                            @endif
                                                                        </small>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    No hay grupos disponibles. 
                                                    <a href="{{ route('grupos.create') }}" target="_blank">Crear un grupo</a> primero.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    @error('grupos')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Información de la Entrega</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Creada:</strong> {{ $entrega->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Última actualización:</strong> {{ $entrega->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <small><strong>Grupos actualmente asignados:</strong> {{ $entrega->grupos->count() }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('entregas.show', $entrega) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver
                            </a>
                            <div>
                                <a href="{{ route('entregas.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-list me-1"></i>
                                    Lista de Entregas
                                </a>
                                <button type="submit" class="btn btn-warning">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-validación del formulario
    const form = document.querySelector('form');
    const tituloInput = document.getElementById('titulo');
    const gruposCheckboxes = document.querySelectorAll('input[name="grupos[]"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    function validateForm() {
        const tituloValido = tituloInput.value.trim().length > 0;
        const gruposSeleccionados = Array.from(gruposCheckboxes).some(cb => cb.checked);
        
        if (submitBtn) {
            submitBtn.disabled = !(tituloValido && gruposSeleccionados);
        }
    }

    if (tituloInput) {
        tituloInput.addEventListener('input', validateForm);
    }
    
    gruposCheckboxes.forEach(cb => {
        cb.addEventListener('change', validateForm);
    });

    // Validación inicial
    validateForm();
});
</script>
@endsection
