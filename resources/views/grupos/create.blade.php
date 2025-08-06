@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Encabezado -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-plus text-white"></i>
                </div>
                <div>
                    <h2 class="mb-0 text-dark fw-bold">Crear Nuevo Grupo</h2>
                    <p class="text-muted mb-0">Organiza a los estudiantes en grupos académicos</p>
                </div>
            </div>

            <!-- Tarjeta principal -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Mensajes de error -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                <h6 class="mb-0 text-danger fw-bold">Por favor, corrige los siguientes errores:</h6>
                            </div>
                            <ul class="mb-0 ms-3">
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form action="{{ route('grupos.store') }}" method="POST" id="grupoForm">
                        @csrf
                        
                        <!-- Campo Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-semibold text-dark">
                                <i class="fas fa-layer-group text-info me-2"></i>Nombre del Grupo
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control form-control-lg" 
                                   value="{{ old('nombre') }}"
                                   placeholder="Ej: Grupo A, 1° Año Turno Mañana, Informática 2024"
                                   maxlength="100"
                                   required>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Elige un nombre descriptivo que identifique claramente al grupo
                            </div>
                            <div id="nombreHelp" class="form-text text-success d-none">
                                <i class="fas fa-check-circle me-1"></i>
                                Nombre válido y descriptivo
                            </div>
                        </div>

                        <!-- Campo Número -->
                        <div class="mb-4">
                            <label for="numero" class="form-label fw-semibold text-dark">
                                <i class="fas fa-hashtag text-warning me-2"></i>Número de Grupo
                            </label>
                            <input type="number" 
                                   name="numero" 
                                   id="numero" 
                                   class="form-control form-control-lg" 
                                   value="{{ old('numero') }}"
                                   placeholder="Ej: 1, 2, 3..."
                                   min="1"
                                   max="999"
                                   required>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Número único para identificar y ordenar el grupo (1-999)
                            </div>
                            <div id="numeroHelp" class="form-text text-success d-none">
                                <i class="fas fa-check-circle me-1"></i>
                                Número válido
                            </div>
                        </div>

                        <!-- Vista previa del grupo -->
                        <div id="groupPreview" class="d-none">
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-dark mb-2">
                                        <i class="fas fa-eye text-info me-2"></i>Vista previa del grupo
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-layer-group text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0" id="previewNombre">-</h6>
                                            <small class="text-muted">Número: <span id="previewNumero">-</span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="{{ route('grupos.index') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-info btn-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Crear Grupo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const numeroInput = document.getElementById('numero');
    const groupPreview = document.getElementById('groupPreview');
    const nombreHelp = document.getElementById('nombreHelp');
    const numeroHelp = document.getElementById('numeroHelp');

    // Validación en tiempo real para el nombre
    nombreInput.addEventListener('input', function() {
        const valor = this.value.trim();
        
        if (valor.length >= 3) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
            nombreHelp.classList.remove('d-none');
        } else if (valor.length > 0) {
            this.classList.remove('is-valid', 'is-invalid');
            nombreHelp.classList.add('d-none');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
            nombreHelp.classList.add('d-none');
        }
        
        updatePreview();
    });

    // Validación en tiempo real para el número
    numeroInput.addEventListener('input', function() {
        const valor = parseInt(this.value);
        
        if (valor >= 1 && valor <= 999) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
            numeroHelp.classList.remove('d-none');
        } else if (this.value.length > 0) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            numeroHelp.classList.add('d-none');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
            numeroHelp.classList.add('d-none');
        }
        
        updatePreview();
    });

    // Función para actualizar la vista previa
    function updatePreview() {
        const nombre = nombreInput.value.trim();
        const numero = numeroInput.value.trim();
        
        if (nombre || numero) {
            document.getElementById('previewNombre').textContent = nombre || 'Sin nombre';
            document.getElementById('previewNumero').textContent = numero || '-';
            groupPreview.classList.remove('d-none');
        } else {
            groupPreview.classList.add('d-none');
        }
    }

    // Capitalizar primera letra de cada palabra en el nombre
    nombreInput.addEventListener('blur', function() {
        if (this.value.trim()) {
            const palabras = this.value.trim().split(' ');
            const nombreCapitalizado = palabras.map(palabra => {
                return palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase();
            }).join(' ');
            this.value = nombreCapitalizado;
            updatePreview();
        }
    });

    // Confirmar envío del formulario
    document.getElementById('grupoForm').addEventListener('submit', function(e) {
        const nombre = nombreInput.value.trim();
        const numero = numeroInput.value.trim();
        
        if (nombre && numero) {
            const mensaje = `¿Confirmas que deseas crear el grupo "${nombre}" con el número ${numero}?`;
            if (!confirm(mensaje)) {
                e.preventDefault();
            }
        }
    });

    // Sugerencias de nombres basadas en el número
    numeroInput.addEventListener('change', function() {
        const numero = parseInt(this.value);
        if (numero && !nombreInput.value.trim()) {
            let sugerencia = '';
            
            if (numero <= 5) {
                sugerencia = `Grupo ${numero}`;
            } else if (numero <= 10) {
                sugerencia = `Curso ${numero}`;
            } else {
                sugerencia = `Grupo ${numero}`;
            }
            
            // Mostrar sugerencia sutil
            nombreInput.placeholder = `Sugerencia: ${sugerencia}`;
        }
    });
});
</script>
@endpush
@endsection