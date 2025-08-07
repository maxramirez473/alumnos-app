@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Perfil
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm float-end">
                            <i class="fas fa-arrow-left me-1"></i>Volver al Perfil
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Sección de imagen de perfil -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Imagen de Perfil</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="current-picture mb-3">
                                            @if($user->hasProfilePicture())
                                                <img src="{{ $user->profile_picture_url }}" 
                                                     alt="Foto de perfil actual" 
                                                     class="img-fluid rounded-circle"
                                                     id="current-picture"
                                                     style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #dee2e6;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-light"
                                                     id="current-picture"
                                                     style="width: 150px; height: 150px; border: 3px solid #dee2e6; margin: 0 auto;">
                                                    <i class="fas fa-user fa-4x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="profile_picture" class="form-label">Nueva Imagen</label>
                                            <input type="file" 
                                                   class="form-control @error('profile_picture') is-invalid @enderror" 
                                                   id="profile_picture" 
                                                   name="profile_picture" 
                                                   accept="image/*"
                                                   onchange="previewImage(event)">
                                            @error('profile_picture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Formatos: JPG, PNG, GIF. Máximo 2MB.
                                            </small>
                                        </div>
                                        
                                        @if($user->hasProfilePicture())
                                            <div class="mb-2">
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        onclick="removeCurrentPicture()">
                                                    <i class="fas fa-times me-1"></i>Quitar Imagen Actual
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Información personal -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Información Personal</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre Completo</label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $user->name) }}" 
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo Electrónico</label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $user->email) }}" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Rol Actual</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-{{ $user->rol === 'admin' ? 'danger' : 'primary' }}">
                                                    {{ ucfirst($user->rol) }}
                                                </span>
                                                <small class="text-muted ms-2">El rol no se puede modificar desde aquí.</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Cambio de contraseña -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Cambiar Contraseña</h6>
                                        <small class="text-muted">Deja en blanco si no quieres cambiar la contraseña</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Contraseña Actual</label>
                                            <input type="password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" 
                                                   name="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Nueva Contraseña</label>
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const currentPicture = document.getElementById('current-picture');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (currentPicture.tagName === 'IMG') {
                currentPicture.src = e.target.result;
            } else {
                // Reemplazar el div con una imagen
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Vista previa';
                img.className = 'img-fluid rounded-circle';
                img.id = 'current-picture';
                img.style.cssText = 'width: 150px; height: 150px; object-fit: cover; border: 3px solid #dee2e6;';
                currentPicture.parentNode.replaceChild(img, currentPicture);
            }
        };
        reader.readAsDataURL(file);
    }
}

function removeCurrentPicture() {
    if (confirm('¿Estás seguro de que quieres quitar la imagen actual?')) {
        // Limpiar el input de archivo
        document.getElementById('profile_picture').value = '';
        
        // Reemplazar la imagen con el ícono de usuario
        const currentPicture = document.getElementById('current-picture');
        const div = document.createElement('div');
        div.className = 'd-flex align-items-center justify-content-center rounded-circle bg-light';
        div.id = 'current-picture';
        div.style.cssText = 'width: 150px; height: 150px; border: 3px solid #dee2e6; margin: 0 auto;';
        div.innerHTML = '<i class="fas fa-user fa-4x text-muted"></i>';
        currentPicture.parentNode.replaceChild(div, currentPicture);
    }
}
</script>
@endpush
@endsection
