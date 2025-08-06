@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Encabezado -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-file-import text-white"></i>
                </div>
                <div>
                    <h2 class="mb-0 text-dark fw-bold">Importar Alumnos</h2>
                    <p class="text-muted mb-0">Carga masiva de estudiantes desde archivo Excel o CSV</p>
                </div>
            </div>

            <!-- Mensajes de estado -->
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        <h6 class="mb-0 text-danger fw-bold">Errores encontrados en el archivo:</h6>
                    </div>
                    <ul class="mb-0 ms-3">
                        @foreach ($errors->all() as $error)
                            <li class="text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>¡Importación exitosa!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-3 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Tarjeta principal -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('alumnos.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <!-- Zona de carga de archivo -->
                        <div class="mb-4">
                            <label for="file" class="form-label fw-semibold text-dark">
                                <i class="fas fa-cloud-upload-alt text-primary me-2"></i>Seleccionar Archivo
                            </label>
                            <div class="upload-area border-2 border-dashed border-primary rounded-3 p-4 text-center position-relative" id="uploadArea">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="text-dark mb-2">Arrastra tu archivo aquí</h5>
                                    <p class="text-muted mb-3">o haz clic para seleccionar un archivo</p>
                                    <input type="file" 
                                           class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0" 
                                           id="file" 
                                           name="file" 
                                           accept=".xlsx,.xls,.csv" 
                                           required>
                                    <div class="d-flex justify-content-center gap-2 mb-2">
                                        <span class="badge bg-success">Excel (.xlsx)</span>
                                        <span class="badge bg-info">Excel (.xls)</span>
                                        <span class="badge bg-warning text-dark">CSV (.csv)</span>
                                    </div>
                                    <small class="text-muted">Tamaño máximo: 10MB</small>
                                </div>
                                
                                <!-- Vista previa del archivo -->
                                <div class="file-preview d-none">
                                    <div class="d-flex align-items-center justify-content-between bg-light rounded p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-excel fa-2x text-success me-3"></i>
                                            <div>
                                                <h6 class="mb-0" id="fileName"></h6>
                                                <small class="text-muted" id="fileSize"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFile()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Archivos soportados: Excel (.xlsx, .xls) y CSV (.csv)
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <span class="btn-text">
                                    <i class="fas fa-file-import me-2"></i>Importar Alumnos
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información del formato -->
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <h5 class="text-dark mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>Formato del Archivo
                    </h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-white rounded shadow-sm">
                                <i class="fas fa-hashtag fa-2x text-primary mb-2"></i>
                                <h6 class="fw-bold">Legajo</h6>
                                <small class="text-muted">Número entero único</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-white rounded shadow-sm">
                                <i class="fas fa-user fa-2x text-success mb-2"></i>
                                <h6 class="fw-bold">Nombre</h6>
                                <small class="text-muted">Texto (máx. 150 chars)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-white rounded shadow-sm">
                                <i class="fas fa-envelope fa-2x text-warning mb-2"></i>
                                <h6 class="fw-bold">Email</h6>
                                <small class="text-muted">Email válido y único</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-lightbulb me-2"></i>Instrucciones Importantes
                        </h6>
                        <ul class="mb-0">
                            <li>La <strong>primera fila</strong> debe contener los nombres de las columnas exactos: <code>legajo</code>, <code>nombre</code>, <code>email</code></li>
                            <li>Los <strong>legajos</strong> deben ser números únicos en el sistema</li>
                            <li>Los <strong>emails</strong> deben ser válidos y únicos</li>
                            <li>Se <strong>omitirán</strong> las filas con datos duplicados o inválidos</li>
                        </ul>
                    </div>

                    <!-- Ejemplo visual -->
                    <div class="mt-3">
                        <h6 class="text-dark mb-2">
                            <i class="fas fa-table me-2"></i>Ejemplo de estructura:
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>legajo</th>
                                        <th>nombre</th>
                                        <th>email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1001</td>
                                        <td>García López, María Elena</td>
                                        <td>maria.garcia@ejemplo.com</td>
                                    </tr>
                                    <tr>
                                        <td>1002</td>
                                        <td>Rodríguez Pérez, Juan Carlos</td>
                                        <td>juan.rodriguez@ejemplo.com</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de descarga de plantilla -->
            <div class="text-center mt-4">
                <a href="#" class="btn btn-outline-primary" onclick="downloadTemplate()">
                    <i class="fas fa-download me-2"></i>Descargar Plantilla Excel
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.upload-area {
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #0d6efd !important;
    background-color: rgba(13, 110, 253, 0.05);
}

.upload-area.dragover {
    border-color: #198754 !important;
    background-color: rgba(25, 135, 84, 0.1);
}

.upload-area input[type="file"] {
    cursor: pointer;
}

.file-preview {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const uploadArea = document.getElementById('uploadArea');
    const uploadContent = uploadArea.querySelector('.upload-content');
    const filePreview = uploadArea.querySelector('.file-preview');
    const submitBtn = document.getElementById('submitBtn');
    const importForm = document.getElementById('importForm');

    // Manejar drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // Manejar selección de archivo
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        // Validar tipo de archivo
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                             'application/vnd.ms-excel', 
                             'text/csv'];
        
        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
            alert('Por favor selecciona un archivo Excel (.xlsx, .xls) o CSV (.csv)');
            return;
        }

        // Validar tamaño (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('El archivo es muy grande. El tamaño máximo es 10MB.');
            return;
        }

        // Mostrar preview
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        
        // Cambiar icono según tipo
        const icon = filePreview.querySelector('i');
        if (file.name.endsWith('.csv')) {
            icon.className = 'fas fa-file-csv fa-2x text-info me-3';
        } else {
            icon.className = 'fas fa-file-excel fa-2x text-success me-3';
        }

        uploadContent.classList.add('d-none');
        filePreview.classList.remove('d-none');
        submitBtn.disabled = false;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Manejar envío del formulario
    importForm.addEventListener('submit', function(e) {
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        submitBtn.disabled = true;
    });
});

// Función para remover archivo
function removeFile() {
    const fileInput = document.getElementById('file');
    const uploadArea = document.getElementById('uploadArea');
    const uploadContent = uploadArea.querySelector('.upload-content');
    const filePreview = uploadArea.querySelector('.file-preview');
    const submitBtn = document.getElementById('submitBtn');

    fileInput.value = '';
    uploadContent.classList.remove('d-none');
    filePreview.classList.add('d-none');
    submitBtn.disabled = true;
}

// Función para descargar plantilla
function downloadTemplate() {
    // Crear contenido CSV
    const csvContent = "legajo,nombre,email\n1001,García López María Elena,maria.garcia@ejemplo.com\n1002,Rodríguez Pérez Juan Carlos,juan.rodriguez@ejemplo.com";
    
    // Crear y descargar archivo
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'plantilla_alumnos.csv');
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endpush
@endsection