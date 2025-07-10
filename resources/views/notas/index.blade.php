@extends('layouts.app')
@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Notas</h4>
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

                    @if($notas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Valor</th>
                                        <th>Alumno</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notas as $nota)
                                        <tr>
                                            <td>{{ $nota->concepto->nombre }}</td>
                                            <td>{{ $nota->nota }}</td>
                                            <td>{{ $nota->alumno->nombre }}</td>
                                            <td>{{ $nota->estado }}</td>
                                            <td class="">
                                                <div class=" d-flex justify-content-between" role="group">
                                                    <a href="{{ route('notas.edit', $nota->id) }}" 
                                                        class="btn btn-warning btn-sm" 
                                                        title="Editar">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                    <form action="{{ route('notas.destroy', $nota->id) }}" 
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
                            <h5>No hay notas</h5>
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    <div class="mt-4 d-flex justify-content-between">
                        <div>
                            <a href="{{ route('notas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Notas
                            </a>
                        </div>
                        <div>
                            <span class="badge bg-secondary fs-6">
                                Total de alumnos: {{ $notas->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection