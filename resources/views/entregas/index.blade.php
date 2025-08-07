@extends('layouts.app')

@section('title', 'Entregas Realizadas')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">
                    <i class="fas fa-tasks text-primary me-2"></i>
                    Entregas Realizadas por Grupos
                </h1>
                <a href="{{ route('entregas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Nueva Entrega
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($entregas->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Entrega</th>
                                        <th>Descripción</th>
                                        <th>Fecha Límite</th>
                                        <th>Grupos Asignados</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entregas as $entrega)
                                        <tr>
                                            <td>
                                                <strong class="text-primary">{{ $entrega->titulo }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ Str::limit($entrega->descripcion ?? 'Sin descripción', 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($entrega->fecha_limite)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($entrega->fecha_limite)->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Sin fecha límite</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($entrega->grupos->count() > 0)
                                                    <span class="badge bg-success">
                                                        {{ $entrega->grupos->count() }} grupo(s)
                                                    </span>
                                                    <div class="mt-1">
                                                        @foreach($entrega->grupos->take(3) as $grupo)
                                                            <small class="text-muted d-block">
                                                                <i class="fas fa-users me-1"></i>
                                                                {{ $grupo->nombre }}
                                                            </small>
                                                        @endforeach
                                                        @if($entrega->grupos->count() > 3)
                                                            <small class="text-muted">
                                                                <i>y {{ $entrega->grupos->count() - 3 }} más...</i>
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning">
                                                        Sin grupos asignados
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('entregas.show', $entrega) }}" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('entregas.edit', $entrega) }}" 
                                                       class="btn btn-outline-warning btn-sm"
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm"
                                                            onclick="confirmarEliminacion({{ $entrega->id }}, '{{ addslashes($entrega->titulo) }}')"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card text-center shadow-sm">
                    <div class="card-body py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay entregas registradas</h5>
                        <p class="text-muted mb-4">Comience creando su primera entrega para grupos.</p>
                        <a href="{{ route('entregas.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Crear Primera Entrega
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar la entrega <strong id="nombreEntrega"></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id, titulo) {
    document.getElementById('nombreEntrega').textContent = titulo;
    document.getElementById('formEliminar').action = `/entregas/${id}`;
    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>
@endsection
