@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>Lista de Alumnos
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('alumnos.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>Nuevo Alumno
                            </a>
                            <a href="{{ route('alumnos.import.form') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-upload me-1"></i>Importar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($alumnos->count() > 0)
                        <!-- Búsqueda simple -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Buscar por nombre, email o legajo...">
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="badge bg-secondary fs-6">
                                    Total: <span id="totalCount">{{ $alumnos->count() }}</span> alumnos
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="alumnosTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-hashtag me-1"></i>Legajo</th>
                                        <th><i class="fas fa-user me-1"></i>Nombre</th>
                                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th><i class="fas fa-users me-1"></i>Grupo</th>
                                        <th class="text-center"><i class="fas fa-star me-1"></i>Promedio</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alumnos as $alumno)
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $alumno->legajo }}</span></td>
                                            <td class="fw-semibold">{{ $alumno->nombre }}</td>
                                            <td>{{ $alumno->email }}</td>
                                            <td>
                                                @if($alumno->grupo)
                                                    <span class="badge bg-info">{{ $alumno->grupo->nombre }}</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Sin asignar</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($alumno->notas->count() > 0)
                                                    @php
                                                        $promedio = round($alumno->notas->avg('nota'), 2);
                                                        $colorClass = $promedio >= 7 ? 'success' : ($promedio >= 5 ? 'warning' : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $colorClass }}">{{ $promedio }}</span>
                                                    <br><small class="text-muted">({{ $alumno->notas->count() }} notas)</small>
                                                @else
                                                    <span class="text-muted">Sin notas</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('alumnos.edit', $alumno->id) }}" 
                                                        class="btn btn-warning btn-sm" 
                                                        title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            title="Eliminar"
                                                            onclick="confirmDelete('{{ route('alumnos.destroy', $alumno->id) }}', '{{ $alumno->nombre }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-3x mb-3 text-info"></i>
                            <h5>No hay alumnos registrados</h5>
                            <p>Comienza agregando alumnos usando los botones de arriba.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación mejorado -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                <h6>¿Estás seguro de eliminar al alumno?</h6>
                <p class="text-muted mb-0" id="deleteStudentName"></p>
                <div class="alert alert-warning mt-3">
                    <small><i class="fas fa-info-circle me-1"></i>Esta acción no se puede deshacer.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('alumnosTable');
    const totalCount = document.getElementById('totalCount');
    
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            let visibleCount = 0;

            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (totalCount) {
                totalCount.textContent = visibleCount;
            }
        });
    }
});

// Función para confirmar eliminación
function confirmDelete(url, studentName) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteStudentName').textContent = studentName;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endpush
@endsection