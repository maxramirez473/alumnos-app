# Sistema de Gestión de Alumnos

## Descripción del Proyecto

Sistema web desarrollado para la gestión integral de alumnos, notas, entregas y grupos académicos. La aplicación permite administrar estudiantes, registrar calificaciones, gestionar entregas de trabajos y organizar grupos de estudio de manera eficiente.

## Tecnologías Utilizadas

### Backend
- **PHP 8.2+** - Lenguaje de programación principal
- **Laravel 12.x** - Framework PHP para desarrollo web
- **MariaDB** - Base de datos relacional (con XAMPP)
- **Maatwebsite/Excel** - Importación y exportación de archivos Excel

### Frontend
- **Blade Templates** - Motor de plantillas de Laravel
- **Bootstrap 5.3.0** - Framework CSS para estilos y componentes
- **Vite** - Bundler de módulos y herramienta de desarrollo
- **Font Awesome 6.4.0** - Librería de iconos
- **JavaScript/Axios** - Para peticiones AJAX

### Herramientas de Desarrollo
- **Composer** - Gestor de dependencias PHP
- **NPM** - Gestor de paquetes JavaScript
- **PHPUnit** - Framework de testing para PHP
- **Laravel Pint** - Linter de código PHP
- **XAMPP** - Entorno de desarrollo local con Apache, MariaDB y PHP

## Funcionalidades Principales

- **Gestión de Alumnos**: Registro, edición y eliminación de estudiantes
- **Gestión de Grupos**: Organización de alumnos por grupos académicos
- **Sistema de Notas**: Registro y seguimiento de calificaciones
- **Gestión de Entregas**: Control de trabajos y asignaciones
- **Conceptos Académicos**: Manejo de diferentes tipos de evaluaciones
- **Importación Excel**: Carga masiva de datos desde archivos Excel
- **Autenticación**: Sistema de login y registro de usuarios
- **Perfiles de Usuario**: Gestión de información personal

## Metodologías y Patrones Aplicados

### Arquitectura
- **MVC (Model-View-Controller)** - Patrón arquitectónico principal
- **Eloquent ORM** - Mapeo objeto-relacional para base de datos
- **Service Classes** - Lógica de negocio encapsulada en servicios
- **Form Requests** - Validación de datos de entrada

### Principios de Desarrollo
- **SOLID** - Principios de diseño orientado a objetos
- **DRY (Don't Repeat Yourself)** - Evitar duplicación de código
- **Convention over Configuration** - Convenciones de Laravel
- **RESTful Routes** - Rutas siguiendo convenciones REST

## Instalación y Configuración

### Prerrequisitos
- PHP 8.2 o superior
- Composer
- Node.js y NPM
- XAMPP (Apache + MariaDB + PHP)
- Git

### Pasos de Instalación

1. **Clonar el repositorio**
   ```powershell
   git clone https://github.com/maxramirez473/alumnos-app.git
   cd alumnos-app
   ```

2. **Instalar dependencias de PHP**
   ```powershell
   composer install
   ```

3. **Instalar dependencias de JavaScript**
   ```powershell
   npm install
   ```

4. **Configurar variables de entorno**
   ```powershell
   copy .env.example .env
   ```
   
   Editar el archivo `.env` con la configuración de tu base de datos MariaDB:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=alumnos_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generar clave de aplicación**
   ```powershell
   php artisan key:generate
   ```

6. **Ejecutar migraciones**
   ```powershell
   php artisan migrate
   ```

7. **Ejecutar seeders (opcional)**
   ```powershell
   php artisan db:seed
   ```

8. **Crear enlace simbólico para storage**
   ```powershell
   php artisan storage:link
   ```

## Ejecutar la Aplicación

### Entorno de Desarrollo

1. **Iniciar servidor de Laravel**
   ```powershell
   php artisan serve
   ```

2. **Compilar assets (en otra terminal)**
   ```powershell
   npm run dev
   ```

La aplicación estará disponible en `http://localhost:8000`

### Entorno de Producción

1. **Compilar assets para producción**
   ```powershell
   npm run build
   ```

2. **Optimizar aplicación**
   ```powershell
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Estructura del Proyecto

```
app/
├── Http/Controllers/     # Controladores
├── Models/              # Modelos Eloquent
├── Imports/             # Clases de importación Excel
└── Services/            # Lógica de negocio

resources/
├── views/               # Plantillas Blade
├── css/                 # Estilos CSS
└── js/                  # JavaScript

database/
├── migrations/          # Migraciones de BD
└── seeders/            # Datos de prueba

routes/
└── web.php             # Rutas web
```

## Testing

Ejecutar las pruebas unitarias:
```powershell
php artisan test
```

## Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Contacto

- **Repositorio**: https://github.com/maxramirez473/alumnos-app
- **Autor**: maxramirez473