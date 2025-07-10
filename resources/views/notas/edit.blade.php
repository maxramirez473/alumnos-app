@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <h2>Editar Nota</h2>
    <form action="{{ route('notas.update') }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id" value="{{ $nota->id }}">

        <div class="mb-3">
            <label for="nota" class="form-label">Valor</label>
            <input type="number" name="nota" id="nota" class="form-control" value="{{ old('nombre', $nota->nota) }}" required>
        </div>

        <div class="mb-3">
            <label for="alumno_id" class="form-label">Alumno</label>
            <select name="alumno_id" id="alumno_id" class="form-select" required>
                <option value="">Seleccione un alumno</option>
                @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->id }}" {{ $nota->alumno_id == $alumno->id ? 'selected' : '' }}>
                        {{ $alumno->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="concepto_id" class="form-label">Concepto</label>
            <select name="concepto_id" id="concepto_id" class="form-select" required>
                <option value="">Seleccione un grupo</option>
                @foreach($conceptos as $concepto)
                    <option value="{{ $concepto->id }}" {{ $nota->concepto_id == $concepto->id ? 'selected' : '' }}>
                        {{ $concepto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection