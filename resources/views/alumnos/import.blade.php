@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Importar Alumnos</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('alumnos.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Seleccionar archivo Excel/CSV</label>
            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Importar Alumnos</button>
        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <div class="mt-4">
        <h5>Formato del archivo:</h5>
        <p>El archivo Excel debe contener exactamente 3 columnas con estos nombres:</p>
        <ul>
            <li><strong>legajo</strong> - Número entero único</li>
            <li><strong>nombre</strong> - Texto (máximo 150 caracteres)</li>
            <li><strong>email</strong> - Email válido y único (máximo 150 caracteres)</li>
        </ul>
        <p><em>La primera fila debe contener los nombres de las columnas.</em></p>
    </div>
</div>
@endsection