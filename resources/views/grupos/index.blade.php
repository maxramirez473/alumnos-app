@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Encabezado -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="fas fa-layer-group text-white"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 text-dark fw-bold">Gestión de Grupos</h2>
                        <p class="text-muted mb-0">Administra los grupos académicos del sistema</p>
                    </div>
                </div>
                <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Grupo
                </a>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 bg-primary text-white">
                        <div class="card-body text-center py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-layer-group fa-2x me-3"></i>
                                <div>
                                    <h3 class="mb-0">{{ $grupos->count() }}</h3>
                                    <small class="opacity-75">Total Grupos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-success text-white">
                        <div class="card-body text-center py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-users fa-2x me-3"></i>
                                <div>
                                    <h3 class="mb-0">{{ $grupos->sum(function($grupo) { return $grupo->alumnos->count(); }) }}</h3>
                                    <small class="opacity-75">Alumnos Asignados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-warning text-white">
                        <div class="card-body text-center py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <h3 class="mb-0">{{ $grupos->filter(function($grupo) { return $grupo->alumnos->count() == 0; })->count() }}</h3>
                                    <small class="opacity-75">Grupos Vacíos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes de estado -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Búsqueda simple -->
            @if($grupos->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Buscar grupo por nombre o número...">
                            </div>
                            <div class="col-md-6 text-end">
                                <span class="badge bg-secondary fs-6">
                                    Mostrando <span id="showingCount">{{ $grupos->count() }}</span> de {{ $grupos->count() }} grupos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabla de grupos -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @if($grupos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="gruposTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 text-muted fw-semibold py-3">
                                            <i class="fas fa-hashtag me-2"></i>Número
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3">
                                            <i class="fas fa-layer-group me-2"></i>Nombre del Grupo
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3 text-center">
                                            <i class="fas fa-users me-2"></i>Alumnos
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3 text-center">
                                            <i class="fas fa-cogs me-2"></i>Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grupos as $grupo)
                                        <tr class="align-middle">
                                            <td class="py-3">
                                                <span class="badge bg-info fs-6">{{ $grupo->numero }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-layer-group text-info"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark">{{ $grupo->nombre }}</h6>
                                                        <small class="text-muted">ID: {{ $grupo->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($grupo->alumnos && $grupo->alumnos->count() > 0)
                                                    <span class="badge bg-success fs-6">{{ $grupo->alumnos->count() }} alumno(s)</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Sin alumnos</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('grupos.edit', $grupo->id) }}" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       title="Editar grupo"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            title="Eliminar grupo"
                                                            data-bs-toggle="tooltip"
                                                            onclick="confirmDelete('{{ route('grupos.destroy', $grupo->id) }}', '{{ $grupo->nombre }}', {{ $grupo->alumnos ? $grupo->alumnos->count() : 0 }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @if($grupo->alumnos && $grupo->alumnos->count() > 0)
                                                        <a href="#" 
                                                           class="btn btn-outline-info btn-sm" 
                                                           title="Ver alumnos del grupo"
                                                           data-bs-toggle="tooltip"
                                                           onclick="showGroupStudents('{{ $grupo->nombre }}', {{ $grupo->alumnos->toJson() }})">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-layer-group fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No hay grupos registrados</h4>
                            <p class="text-muted mb-4">Comienza creando grupos para organizar a los estudiantes</p>
                            <a href="{{ route('grupos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Crear Primer Grupo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fas fa-layer-group fa-3x text-danger"></i>
                </div>
                <h6 class="mb-3">¿Estás seguro de eliminar el grupo?</h6>
                <p class="text-muted mb-0" id="deleteGroupName"></p>
                <div class="alert alert-warning mt-3" id="studentsWarning" style="display: none;">
                    <small><i class="fas fa-info-circle me-1"></i>Este grupo tiene <span id="studentCount"></span> alumno(s) asignado(s). Serán desasignados del grupo.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar Grupo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver alumnos del grupo -->
<div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="studentsModalLabel">
                    <i class="fas fa-users me-2"></i>Alumnos del Grupo: <span id="modalGroupName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="studentsList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(function(tooltip) {
        new bootstrap.Tooltip(tooltip);
    });

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('gruposTable');
    const showingCount = document.getElementById('showingCount');
    
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

            if (showingCount) {
                showingCount.textContent = visibleCount;
            }
        });
    }
});

// Función para confirmar eliminación
function confirmDelete(url, groupName, studentCount) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteGroupName').textContent = groupName;
    
    const studentsWarning = document.getElementById('studentsWarning');
    const studentCountSpan = document.getElementById('studentCount');
    
    if (studentCount > 0) {
        studentCountSpan.textContent = studentCount;
        studentsWarning.style.display = 'block';
    } else {
        studentsWarning.style.display = 'none';
    }
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Función para mostrar alumnos del grupo
function showGroupStudents(groupName, students) {
    document.getElementById('modalGroupName').textContent = groupName;
    
    let studentsList = '<div class="row g-2">';
    students.forEach(function(student) {
        studentsList += `
            <div class="col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0" style="font-size: 14px;">${student.nombre}</h6>
                                <small class="text-muted">Legajo: ${student.legajo}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    studentsList += '</div>';
    
    if (students.length === 0) {
        studentsList = '<div class="text-center text-muted"><i class="fas fa-users fa-2x mb-2"></i><br>No hay alumnos asignados a este grupo</div>';
    }
    
    document.getElementById('studentsList').innerHTML = studentsList;
    
    var studentsModal = new bootstrap.Modal(document.getElementById('studentsModal'));
    studentsModal.show();
}
</script>
@endpush
@endsection