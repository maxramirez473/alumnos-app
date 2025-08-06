@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Encabezado -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-user-edit text-white"></i>
                </div>
                <div>
                    <h2 class="mb-0 text-dark fw-bold">Editar Alumno</h2>
                    <p class="text-muted mb-0">Actualiza la información del estudiante</p>
                </div>
            </div>

            <!-- Información del alumno actual -->
            <div class="card bg-light border-0 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark">{{ $alumno->nombre }}</h6>
                            <small class="text-muted">Legajo: {{ $alumno->legajo }} | ID: {{ $alumno->id }}</small>
                        </div>
                    </div>
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

                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div class="alert alert-success border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>¡Actualización exitosa!</strong> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form action="{{ route('alumnos.update') }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" value="{{ $alumno->id }}">

                        <!-- Campo Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-semibold text-dark">
                                <i class="fas fa-user text-primary me-2"></i>Nombre Completo del Estudiante
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control form-control-lg" 
                                   value="{{ old('nombre', $alumno->nombre) }}"
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
                                   value="{{ old('email', $alumno->email) }}"
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
                                    <option value="{{ $grupo->id }}" 
                                            {{ (old('grupo_id', $alumno->grupo_id) == $grupo->id) ? 'selected' : '' }}>
                                        {{ $grupo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                El grupo determina las materias y horarios del estudiante
                            </div>
                        </div>

                        <!-- Resumen de cambios -->
                        <div id="changesPreview" class="d-none">
                            <div class="card bg-warning bg-opacity-10 border-warning mb-4">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-warning mb-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Cambios detectados
                                    </h6>
                                    <div id="changesList" class="text-sm"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Actualizar Alumno
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
                            <strong>Nota importante:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Los cambios se guardarán inmediatamente al hacer clic en "Actualizar"</li>
                                <li>Si cambias el email, debe seguir siendo único en el sistema</li>
                                <li>El legajo no se puede modificar una vez asignado</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Historial de notas (si tiene) -->
            @if($alumno->notas && $alumno->notas->count() > 0)
                <div class="card border-0 bg-info bg-opacity-10 mt-4">
                    <div class="card-body p-3">
                        <h6 class="text-info mb-2">
                            <i class="fas fa-chart-line me-2"></i>Rendimiento Académico
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5 class="mb-0 text-info">{{ $alumno->notas->count() }}</h5>
                                    <small class="text-muted">Notas registradas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5 class="mb-0 text-info">{{ round($alumno->notas->avg('nota'), 2) }}</h5>
                                    <small class="text-muted">Promedio general</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5 class="mb-0 text-info">{{ $alumno->notas->max('nota') }}</h5>
                                    <small class="text-muted">Nota más alta</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const grupoSelect = document.getElementById('grupo_id');
    const nombreHelp = document.getElementById('nombreHelp');
    const emailHelp = document.getElementById('emailHelp');
    const changesPreview = document.getElementById('changesPreview');
    const changesList = document.getElementById('changesList');

    // Valores originales para comparar cambios
    const originalValues = {
        nombre: '{{ $alumno->nombre }}',
        email: '{{ $alumno->email }}',
        grupo_id: '{{ $alumno->grupo_id }}'
    };

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
        
        checkForChanges();
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
        
        checkForChanges();
    });

    // Detectar cambios en el grupo
    grupoSelect.addEventListener('change', checkForChanges);

    // Función para detectar y mostrar cambios
    function checkForChanges() {
        const currentValues = {
            nombre: nombreInput.value.trim(),
            email: emailInput.value.trim(),
            grupo_id: grupoSelect.value
        };

        const changes = [];

        if (currentValues.nombre !== originalValues.nombre) {
            changes.push(`<strong>Nombre:</strong> "${originalValues.nombre}" → "${currentValues.nombre}"`);
        }

        if (currentValues.email !== originalValues.email) {
            changes.push(`<strong>Email:</strong> "${originalValues.email}" → "${currentValues.email}"`);
        }

        if (currentValues.grupo_id !== originalValues.grupo_id) {
            const originalGrupo = grupoSelect.querySelector(`option[value="${originalValues.grupo_id}"]`)?.text || 'Sin asignar';
            const newGrupo = grupoSelect.querySelector(`option[value="${currentValues.grupo_id}"]`)?.text || 'Sin asignar';
            changes.push(`<strong>Grupo:</strong> "${originalGrupo}" → "${newGrupo}"`);
        }

        if (changes.length > 0) {
            changesList.innerHTML = changes.join('<br>');
            changesPreview.classList.remove('d-none');
        } else {
            changesPreview.classList.add('d-none');
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
            checkForChanges();
        }
    });

    // Confirmar envío del formulario si hay cambios
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const changesList = document.getElementById('changesList');
        
        if (!changesPreview.classList.contains('d-none')) {
            const confirmMessage = '¿Confirmas que deseas guardar los siguientes cambios?\n\n' + 
                                 changesList.textContent.replace(/→/g, ' -> ');
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        }
    });

    // Verificar cambios iniciales (por si hay old values)
    checkForChanges();
});
</script>
@endpush
@endsection