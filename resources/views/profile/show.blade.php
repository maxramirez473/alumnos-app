@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Mi Perfil</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Imagen de perfil -->
                        <div class="col-md-4 text-center">
                            <div class="profile-picture-container mb-3">
                                @if($user->hasProfilePicture())
                                    <img src="{{ $user->profile_picture_url }}" 
                                         alt="Foto de perfil" 
                                         class="img-fluid rounded-circle profile-picture"
                                         style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #dee2e6;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-light"
                                         style="width: 200px; height: 200px; border: 4px solid #dee2e6; margin: 0 auto;">
                                        <i class="fas fa-user fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="profile-actions">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>Editar Perfil
                                </a>
                                
                                @if($user->hasProfilePicture())
                                    <form action="{{ route('profile.delete-picture') }}" method="POST" class="d-inline mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')">
                                            <i class="fas fa-trash me-1"></i>Eliminar Foto
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Información del usuario -->
                        <div class="col-md-8">
                            <div class="profile-info">
                                <h2 class="mb-3">{{ $user->name }}</h2>
                                
                                <div class="info-group mb-3">
                                    <label class="fw-bold text-muted">Correo Electrónico:</label>
                                    <p class="mb-0">{{ $user->email }}</p>
                                </div>
                                
                                <div class="info-group mb-3">
                                    <label class="fw-bold text-muted">Rol:</label>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $user->rol === 'admin' ? 'danger' : 'primary' }}">
                                            {{ ucfirst($user->rol) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="info-group mb-3">
                                    <label class="fw-bold text-muted">Miembro desde:</label>
                                    <p class="mb-0">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                                
                                @if($user->email_verified_at)
                                    <div class="info-group mb-3">
                                        <label class="fw-bold text-muted">Email verificado:</label>
                                        <p class="mb-0">
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Verificado
                                            </span>
                                        </p>
                                    </div>
                                @else
                                    <div class="info-group mb-3">
                                        <label class="fw-bold text-muted">Email verificado:</label>
                                        <p class="mb-0">
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Pendiente
                                            </span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .profile-picture-container {
        position: relative;
        display: inline-block;
    }
    
    .profile-actions .btn {
        margin: 2px;
    }
    
    .info-group {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .info-group:last-child {
        border-bottom: none;
    }
</style>
@endpush
@endsection
