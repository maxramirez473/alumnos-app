# Funcionalidad de Perfil de Usuario

Esta documentación describe la nueva funcionalidad de perfil de usuario implementada en la aplicación Alumnos App.

## Características Implementadas

### 1. Gestión de Imagen de Perfil
- **Subida de imágenes**: Los usuarios pueden subir imágenes de perfil en formatos JPG, PNG y GIF
- **Tamaño máximo**: 2MB por imagen
- **Redimensionamiento automático**: Las imágenes se redimensionan automáticamente a 400x400 px máximo
- **Almacenamiento**: Las imágenes se guardan en `storage/app/public/media/`
- **Nomenclatura**: Las imágenes se nombran como `{user_id}_{timestamp}.{extension}`

### 2. Vista de Perfil
- **Ruta**: `/profile`
- **Funcionalidades**:
  - Mostrar información del usuario (nombre, email, rol, fecha de registro)
  - Mostrar imagen de perfil o avatar generado automáticamente
  - Enlace para editar perfil
  - Opción para eliminar imagen de perfil

### 3. Edición de Perfil
- **Ruta**: `/profile/edit`
- **Funcionalidades**:
  - Actualizar nombre y email
  - Cambiar contraseña (requiere contraseña actual)
  - Subir nueva imagen de perfil con vista previa
  - Eliminar imagen de perfil existente

### 4. Integración en la Navegación
- El dropdown del usuario en la barra de navegación ahora muestra:
  - Imagen de perfil miniatura (si existe)
  - Enlace "Mi Perfil"
  - Separador visual
  - Opción "Cerrar Sesión"

## Archivos Creados/Modificados

### Controlador
- `app/Http/Controllers/ProfileController.php` - Nuevo controlador para gestión de perfil

### Modelo
- `app/Models/User.php` - Agregados métodos helper para imagen de perfil:
  - `getProfilePictureUrlAttribute()` - URL pública de la imagen
  - `getProfilePicturePathAttribute()` - Ruta completa en el sistema
  - `hasProfilePicture()` - Verificar si tiene imagen

### Rutas
- `routes/web.php` - Agregadas rutas de perfil:
  - `GET /profile` - Ver perfil
  - `GET /profile/edit` - Editar perfil
  - `PUT /profile` - Actualizar perfil
  - `DELETE /profile/picture` - Eliminar imagen

### Vistas
- `resources/views/profile/show.blade.php` - Vista de perfil
- `resources/views/profile/edit.blade.php` - Formulario de edición
- `resources/views/layouts/app.blade.php` - Actualizada navegación

### Directorios
- `storage/app/public/media/` - Directorio para imágenes de perfil
- `public/storage` - Enlace simbólico creado con `php artisan storage:link`

## Uso de la Funcionalidad

### Para Usuarios
1. **Ver perfil**: Hacer clic en el dropdown del usuario → "Mi Perfil"
2. **Editar perfil**: En la vista de perfil, hacer clic en "Editar Perfil"
3. **Cambiar imagen**: En editar perfil, seleccionar archivo en "Nueva Imagen"
4. **Eliminar imagen**: En editar perfil o vista de perfil, usar el botón "Eliminar Foto"

### Para Desarrolladores
```php
// Obtener URL de imagen de perfil
$user = Auth::user();
$profileUrl = $user->profile_picture_url;

// Verificar si tiene imagen
if ($user->hasProfilePicture()) {
    // Usuario tiene imagen personalizada
}

// Obtener ruta completa del archivo
$filePath = $user->profile_picture_path;
```

## Configuración de Storage

La aplicación usa el disco 'public' de Laravel para almacenar las imágenes:

```php
// En config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

## Validaciones Implementadas

### Imagen de Perfil
- Tipo: debe ser imagen (jpeg, png, jpg, gif)
- Tamaño: máximo 2MB
- Validación de contenido: verificación de que sea realmente una imagen

### Actualización de Datos
- Nombre: requerido, máximo 255 caracteres
- Email: requerido, válido, único (excepto el actual)
- Contraseña: opcional, mínimo según reglas de Laravel, confirmación requerida

## Características de Seguridad

1. **Validación de archivos**: Verificación de tipo MIME y contenido real
2. **Nombres únicos**: Prevención de conflictos con timestamp
3. **Limpieza automática**: Eliminación de imágenes anteriores al actualizar
4. **Autenticación requerida**: Todas las rutas protegidas por middleware auth
5. **Validación de contraseña**: Verificación de contraseña actual para cambios

## Avatar Automático

Si el usuario no tiene imagen de perfil, se genera automáticamente un avatar usando el servicio UI-Avatars:
- URL: `https://ui-avatars.com/api/?name={nombre}&size=200&background=6c757d&color=ffffff&rounded=true`
- Muestra las iniciales del nombre del usuario
- Fondo gris con texto blanco
- Forma circular

## Consideraciones de Rendimiento

1. **Redimensionamiento**: Las imágenes se redimensionan automáticamente para optimizar espacio
2. **Compresión**: JPEG al 85% de calidad, PNG optimizado
3. **Limpieza**: Eliminación automática de archivos antiguos
4. **Lazy loading**: Las imágenes en la navegación se cargan bajo demanda
