@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Encabezado -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-plus text-white"></i>
                </div>
                <div>
                    <h2 class="mb-0 text-dark fw-bold">Agregar Nueva Nota</h2>
                    <p class="text-muted mb-0">Registra una nueva calificación para un alumno</p>
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
                    <form action="{{ route('notas.store') }}" method="POST" id="notaForm">
                        @csrf
                        
                        <!-- Campo Nota -->
                        <div class="mb-4">
                            <label for="nota" class="form-label fw-semibold text-dark">
                                <i class="fas fa-star text-warning me-2"></i>Calificación
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       name="nota" 
                                       id="nota" 
                                       class="form-control form-control-lg" 
                                       value="{{ old('nota') }}"
                                       min="0" 
                                       max="10" 
                                       step="0.1"
                                       placeholder="Ej: 8.5"
                                       required>
                                <span class="input-group-text bg-light">/ 10</span>
                            </div>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Ingresa una calificación entre 0.0 y 10.0
                            </div>
                        </div>

                        <!-- Campo Concepto -->
                        <div class="mb-4">
                            <label for="concepto_id" class="form-label fw-semibold text-dark">
                                <i class="fas fa-tags text-info me-2"></i>Concepto de Evaluación
                            </label>
                            <select name="concepto_id" id="concepto_id" class="form-select form-select-lg" required>
                                <option value="">-- Selecciona un concepto --</option>
                                @foreach($conceptos as $concepto)
                                    <option value="{{ $concepto->id }}" {{ old('concepto_id') == $concepto->id ? 'selected' : '' }}>
                                        {{ $concepto->nombre }} - {{ $concepto->descripcion }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Selecciona el tipo de evaluación que estás calificando
                            </div>
                        </div>

                        <!-- Campo Alumno -->
                        <div class="mb-4">
                            <label for="alumno_id" class="form-label fw-semibold text-dark">
                                <i class="fas fa-user-graduate text-success me-2"></i>Estudiante
                            </label>
                            <select name="alumno_id" id="alumno_id" class="form-select form-select-lg" required>
                                <option value="">-- Selecciona un alumno --</option>
                                @foreach($alumnos as $alumno)
                                    <option value="{{ $alumno->id }}" {{ old('alumno_id') == $alumno->id ? 'selected' : '' }}>
                                        {{ $alumno->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Selecciona el estudiante al que le corresponde esta calificación
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="{{ route('notas.index') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Guardar Nota
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    <small class="text-muted">
                        <strong>Consejo:</strong> Asegúrate de verificar los datos antes de guardar. Una vez registrada, la nota formará parte del expediente académico del estudiante.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación en tiempo real para el campo de nota
    const notaInput = document.getElementById('nota');
    
    notaInput.addEventListener('input', function() {
        const valor = parseFloat(this.value);
        
        if (valor < 0 || valor > 10) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value !== '') {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    // Mejorar la búsqueda en selectores (si tienes Select2 disponible)
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#concepto_id, #alumno_id').select2({
            theme: 'bootstrap-5',
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });
    }

    // Confirmar envío del formulario
    document.getElementById('notaForm').addEventListener('submit', function(e) {
        const nota = document.getElementById('nota').value;
        const concepto = document.getElementById('concepto_id').options[document.getElementById('concepto_id').selectedIndex].text;
        const alumno = document.getElementById('alumno_id').options[document.getElementById('alumno_id').selectedIndex].text;
        
        if (nota && concepto && alumno) {
            const mensaje = `¿Confirmas que deseas registrar la nota ${nota} para ${alumno} en ${concepto}?`;
            if (!confirm(mensaje)) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endpush
@endsection