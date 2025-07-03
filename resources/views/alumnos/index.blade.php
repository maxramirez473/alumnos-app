<!-- filepath: c:\Users\totoPC\Desktop\Alumnos-app\resources\views\alumno\index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Lista de Alumnos</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($alumnos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Legajo</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Grupo ID</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alumnos as $alumno)
                                        <tr>
                                            <td>{{ $alumno->id }}</td>
                                            <td>{{ $alumno->legajo }}</td>
                                            <td>{{ $alumno->nombre }}</td>
                                            <td>{{ $alumno->email }}</td>
                                            <td>{{ $alumno->grupo->nombre ?? 'Sin asignar' }}</td>
                                            <td class="">
                                                <div class=" d-flex justify-content-between" role="group">
                                                    <a href="{{ route('alumnos.edit', $alumno->id) }}" 
                                                        class="btn btn-warning btn-sm" 
                                                        title="Editar">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                    <form action="{{ route('alumnos.destroy', $alumno->id) }}" 
                                                            method="POST" 
                                                            class="d-inline"
                                                            onsubmit="return confirm('¿Estás seguro de que quieres eliminar este alumno?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <h5>No hay alumnos registrados</h5>
                            <p>Comienza agregando alumnos usando los botones de abajo.</p>
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    <div class="mt-4 d-flex justify-content-between">
                        <div>
                            <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Nuevo Alumno
                            </a>
                            <a href="{{ route('alumnos.import.form') }}" class="btn btn-success">
                                <i class="fas fa-upload"></i> Importar Alumnos
                            </a>
                        </div>
                        <div>
                            <span class="badge bg-secondary fs-6">
                                Total de alumnos: {{ $alumnos->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación (opcional, alternativa al confirm de JavaScript) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres eliminar este alumno? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Script opcional para usar el modal en lugar del confirm
    function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endsection