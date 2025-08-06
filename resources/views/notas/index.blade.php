@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Encabezado -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="mx-auto">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 text-dark fw-bold">Gestión de Notas</h2>
                        <p class="text-muted mb-0">Administra las calificaciones de los estudiantes</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            @if($notas->count() > 0)
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 bg-warning text-white">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-star fa-2x me-3"></i>
                                    <div>
                                        <h3 class="mb-0">{{ $notas->count() }}</h3>
                                        <small class="opacity-75">Total Notas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-success text-white">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-trophy fa-2x me-3"></i>
                                    <div>
                                        <h3 class="mb-0">{{ round($notas->avg('nota'), 1) }}</h3>
                                        <small class="opacity-75">Promedio General</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-info text-white">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-chart-line fa-2x me-3"></i>
                                    <div>
                                        <h3 class="mb-0">{{ $notas->where('nota', '>=', 7)->count() }}</h3>
                                        <small class="opacity-75">Aprobadas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-danger text-white">
                            <div class="card-body text-center py-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                    <div>
                                        <h3 class="mb-0">{{ $notas->where('nota', '<', 7)->count() }}</h3>
                                        <small class="opacity-75">Desaprobadas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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

            <!-- Filtros y búsqueda -->
            @if($notas->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Buscar por alumno, concepto o nota...">
                            </div>
                            <div class="col-md-3">
                                <select id="conceptoFilter" class="form-select">
                                    <option value="">Todos los conceptos</option>
                                    @foreach($notas->groupBy('concepto.nombre') as $conceptoNombre => $notasConcepto)
                                        <option value="{{ $conceptoNombre }}">{{ $conceptoNombre }} ({{ $notasConcepto->count() }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="estadoFilter" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="aprobada">Aprobadas (≥7)</option>
                                    <option value="desaprobada">Desaprobadas (<7)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabla de notas -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    @if($notas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="notasTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 text-muted fw-semibold py-3">
                                            <i class="fas fa-tags me-2"></i>Concepto
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3 text-center">
                                            <i class="fas fa-star me-2"></i>Calificación
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3">
                                            <i class="fas fa-user-graduate me-2"></i>Estudiante
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3 text-center">
                                            <i class="fas fa-check-circle me-2"></i>Estado
                                        </th>
                                        <th class="border-0 text-muted fw-semibold py-3 text-center">
                                            <i class="fas fa-cogs me-2"></i>Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notas as $nota)
                                        <tr class="align-middle">
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-tag text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark">{{ $nota->concepto->nombre }}</h6>
                                                        <small class="text-muted">{{ $nota->concepto->descripcion ?? 'Sin descripción' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                @php
                                                    $colorClass = $nota->nota >= 7 ? 'success' : ($nota->nota >= 5 ? 'warning' : 'danger');
                                                @endphp
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-{{ $colorClass }} fs-5 mb-1">{{ $nota->nota }}/10</span>
                                                    @if($nota->nota >= 9)
                                                        <small class="text-success">Excelente</small>
                                                    @elseif($nota->nota >= 7)
                                                        <small class="text-success">Aprobado</small>
                                                    @elseif($nota->nota >= 5)
                                                        <small class="text-warning">Regular</small>
                                                    @else
                                                        <small class="text-danger">Insuficiente</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-dark">{{ $nota->alumno->nombre }}</h6>
                                                        <small class="text-muted">Legajo: {{ $nota->alumno->legajo }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($nota->nota >= 7)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Aprobada
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Desaprobada
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('notas.edit', $nota->id) }}" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       title="Editar nota"
                                                       data-bs-toggle="tooltip">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            title="Eliminar nota"
                                                            data-bs-toggle="tooltip"
                                                            onclick="confirmDelete('{{ route('notas.destroy', $nota->id) }}', '{{ $nota->concepto->nombre }}', '{{ $nota->alumno->nombre }}', '{{ $nota->nota }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Información de resultados -->
                        <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
                            <div class="text-muted">
                                <small>Mostrando <span id="showingCount">{{ $notas->count() }}</span> de {{ $notas->count() }} notas</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="exportNotes()">
                                    <i class="fas fa-download me-1"></i>Exportar
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-star fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No hay notas registradas</h4>
                            <p class="text-muted mb-4">Comienza evaluando a los estudiantes del sistema</p>
                            <a href="{{ route('notas.create') }}" class="btn btn-warning">
                                <i class="fas fa-plus me-2"></i>Crear Primera Nota
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
                    <i class="fas fa-star fa-3x text-danger"></i>
                </div>
                <h6 class="mb-3">¿Estás seguro de eliminar esta nota?</h6>
                <div class="alert alert-info">
                    <div><strong>Concepto:</strong> <span id="deleteConcepto"></span></div>
                    <div><strong>Alumno:</strong> <span id="deleteAlumno"></span></div>
                    <div><strong>Nota:</strong> <span id="deleteNota"></span>/10</div>
                </div>
                <div class="alert alert-warning">
                    <small><i class="fas fa-info-circle me-1"></i>Esta acción no se puede deshacer y afectará el promedio del estudiante.</small>
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
                        <i class="fas fa-trash me-1"></i>Eliminar Nota
                    </button>
                </form>
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

    // Filtros
    const searchInput = document.getElementById('searchInput');
    const conceptoFilter = document.getElementById('conceptoFilter');
    const estadoFilter = document.getElementById('estadoFilter');
    const table = document.getElementById('notasTable');
    const showingCount = document.getElementById('showingCount');
    
    function filterTable() {
        if (!table) return;
        
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const conceptoSelected = conceptoFilter ? conceptoFilter.value : '';
        const estadoSelected = estadoFilter ? estadoFilter.value : '';
        const rows = table.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(function(row) {
            const concepto = row.cells[0].textContent.toLowerCase();
            const nota = parseFloat(row.cells[1].textContent);
            const alumno = row.cells[2].textContent.toLowerCase();
            
            let showRow = true;

            // Filtro de búsqueda
            if (searchTerm && !concepto.includes(searchTerm) && !alumno.includes(searchTerm) && !nota.toString().includes(searchTerm)) {
                showRow = false;
            }

            // Filtro de concepto
            if (conceptoSelected && !concepto.includes(conceptoSelected.toLowerCase())) {
                showRow = false;
            }

            // Filtro de estado
            if (estadoSelected === 'aprobada' && nota < 7) {
                showRow = false;
            } else if (estadoSelected === 'desaprobada' && nota >= 7) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        });

        if (showingCount) {
            showingCount.textContent = visibleCount;
        }
    }

    // Event listeners para filtros
    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (conceptoFilter) conceptoFilter.addEventListener('change', filterTable);
    if (estadoFilter) estadoFilter.addEventListener('change', filterTable);
});

// Función para confirmar eliminación
function confirmDelete(url, concepto, alumno, nota) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteConcepto').textContent = concepto;
    document.getElementById('deleteAlumno').textContent = alumno;
    document.getElementById('deleteNota').textContent = nota;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Función para limpiar filtros
function clearFilters() {
    const searchInput = document.getElementById('searchInput');
    const conceptoFilter = document.getElementById('conceptoFilter');
    const estadoFilter = document.getElementById('estadoFilter');
    
    if (searchInput) searchInput.value = '';
    if (conceptoFilter) conceptoFilter.value = '';
    if (estadoFilter) estadoFilter.value = '';
    
    // Mostrar todas las filas
    const rows = document.querySelectorAll('#notasTable tbody tr');
    rows.forEach(function(row) {
        row.style.display = '';
    });
    
    // Actualizar contador
    const showingCount = document.getElementById('showingCount');
    if (showingCount) {
        showingCount.textContent = rows.length;
    }
}

// Función para exportar notas
function exportNotes() {
    const table = document.getElementById('notasTable');
    if (!table) return;
    
    const rows = Array.from(table.querySelectorAll('tr:not([style*="display: none"])'));
    
    let csv = 'Concepto,Nota,Alumno,Estado\n';
    
    rows.slice(1).forEach(function(row) { // Omitir header
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const concepto = cells[0].textContent.trim().split('\n')[0];
            const nota = cells[1].textContent.trim().split('/')[0];
            const alumno = cells[2].textContent.trim().split('\n')[0];
            const estado = cells[3].textContent.trim();
            
            csv += `"${concepto}","${nota}","${alumno}","${estado}"\n`;
        }
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'notas_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endpush
@endsection