@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Encabezado -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <div>
                    <h2 class="mb-0 text-dark fw-bold">Registrar Nuevo Alumno</h2>
                    <p class="text-muted mb-0">Agrega un nuevo estudiante al sistema</p>
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
                    <form action="{{ route('alumnos.store') }}" method="POST" id="alumnoForm">
                        @csrf
                        
                        <!-- Campo Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-semibold text-dark">
                                <i class="fas fa-user text-primary me-2"></i>Nombre Completo del Estudiante
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control form-control-lg" 
                                   value="{{ old('nombre') }}"
                                   placeholder="Ej: García López, María Elena"
                                   maxlength="100"
                                   required>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Formato recomendado: Apellido(s), Nombre(s) para mejor organización
                            </div>
                            <div id="nombreHelp" class="form-text text-success d-none">
                                <i class="fas fa-check-circle me-1"></i>
                                Formato correcto detectado
                            </div>
                        </div>

                        <!-- Campo Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-dark">
                                <i class="fas fa-envelope text-info me-2"></i>Correo Electrónico
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control form-control-lg" 
                                   value="{{ old('email') }}"
                                   placeholder="Ej: maria.garcia@ejemplo.com"
                                   maxlength="150"
                                   required>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Este correo será usado para comunicaciones académicas
                            </div>
                            <div id="emailHelp" class="form-text text-success d-none">
                                <i class="fas fa-check-circle me-1"></i>
                                Formato de email válido
                            </div>
                        </div>

                        <!-- Campo Grupo -->
                        <div class="mb-4">
                            <label for="grupo_id" class="form-label fw-semibold text-dark">
                                <i class="fas fa-users text-warning me-2"></i>Grupo Académico
                            </label>
                            <select name="grupo_id" id="grupo_id" class="form-select form-select-lg" required>
                                <option value="">-- Selecciona un grupo --</option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}" {{ old('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                        {{ $grupo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                El grupo determina las materias y horarios del estudiante
                            </div>
                        </div>

                        <!-- Preview del estudiante -->
                        <div id="studentPreview" class="d-none">
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-dark mb-2">
                                        <i class="fas fa-eye text-primary me-2"></i>Vista previa del registro
                                    </h6>
                                    <div class="row text-sm">
                                        <div class="col-md-6">
                                            <strong>Estudiante:</strong> <span id="previewNombre">-</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Email:</strong> <span id="previewEmail">-</span>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <strong>Grupo:</strong> <span id="previewGrupo">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-user-plus me-2"></i>Registrar Alumno
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <div class="d-flex align-items-start">
                    <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                    <div>
                        <small class="text-muted">
                            <strong>Información importante:</strong>
                            <ul class="mb-0 mt-1">
                                <li>El email debe ser único en el sistema</li>
                                <li>Una vez registrado, el alumno podrá ser asignado a evaluaciones</li>
                                <li>El formato del nombre ayuda a mantener el orden alfabético</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const grupoSelect = document.getElementById('grupo_id');
    const studentPreview = document.getElementById('studentPreview');
    const nombreHelp = document.getElementById('nombreHelp');
    const emailHelp = document.getElementById('emailHelp');

    // Validación en tiempo real para el nombre
    nombreInput.addEventListener('input', function() {
        const valor = this.value.trim();
        
        // Verificar formato de apellido, nombre
        if (valor.includes(',') && valor.length > 3) {
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

    // Validación en tiempo real para el email
    emailInput.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (emailRegex.test(this.value)) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
            emailHelp.classList.remove('d-none');
        } else if (this.value.length > 0) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            emailHelp.classList.add('d-none');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
            emailHelp.classList.add('d-none');
        }
        
        updatePreview();
    });

    // Actualizar preview cuando cambie el grupo
    grupoSelect.addEventListener('change', updatePreview);

    // Función para actualizar la vista previa
    function updatePreview() {
        const nombre = nombreInput.value.trim();
        const email = emailInput.value.trim();
        const grupoText = grupoSelect.options[grupoSelect.selectedIndex].text;
        
        if (nombre || email || grupoSelect.value) {
            document.getElementById('previewNombre').textContent = nombre || '-';
            document.getElementById('previewEmail').textContent = email || '-';
            document.getElementById('previewGrupo').textContent = grupoSelect.value ? grupoText : '-';
            studentPreview.classList.remove('d-none');
        } else {
            studentPreview.classList.add('d-none');
        }
    }

    // Capitalizar primera letra de cada palabra en el nombre
    nombreInput.addEventListener('blur', function() {
        if (this.value.trim()) {
            // Capitalizar manteniendo el formato de comas
            const partes = this.value.split(',');
            const partesCapitalizadas = partes.map(parte => {
                return parte.trim().split(' ').map(palabra => {
                    return palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase();
                }).join(' ');
            });
            this.value = partesCapitalizadas.join(', ');
            updatePreview();
        }
    });

    // Confirmar envío del formulario
    document.getElementById('alumnoForm').addEventListener('submit', function(e) {
        const nombre = nombreInput.value.trim();
        const email = emailInput.value.trim();
        const grupo = grupoSelect.options[grupoSelect.selectedIndex].text;
        
        if (nombre && email && grupoSelect.value) {
            const mensaje = `¿Confirmas que deseas registrar a "${nombre}" con email "${email}" en el grupo "${grupo}"?`;
            if (!confirm(mensaje)) {
                e.preventDefault();
            }
        }
    });

    // Mejorar la búsqueda en selector de grupo (si tienes Select2 disponible)
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#grupo_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Selecciona un grupo --',
            allowClear: true
        });
    }
});
</script>
@endpush
@endsection