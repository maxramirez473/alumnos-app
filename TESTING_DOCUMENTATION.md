# 🧪 **GUÍA COMPLETA DE TESTING PARA PRESENTACIÓN**

## 🎯 **PROPÓSITO DE ESTA DOCUMENTACIÓN**

Esta guía está diseñada para tu **presentación s# 4A. Con documentación legible (tests que funcionan)
vendor/bin/phpunit tests/Unit/ tests/Feature/API/ --testdox

# 4B. Con cobertura de código (requiere Xdebug o PCOV instalado)
vendor/bin/phpunit --coverage-html coverage/re testing de aplicaciones**. Aquí encontrarás ejemplos prácticos de todos los tipos de testing implementados en la aplicación Laravel de gestión de alumnos.

---

## 📋 **ÍNDICE DE LA PRESENTACIÓN**

0. [⚙️ Configuración Inicial de PHPUnit](#configuración-inicial-de-phpunit)
1. [🔬 Testing Unitario (Caja Blanca)](#testing-unitario-caja-blanca)
2. [🔗 Testing de Integración](#testing-de-integración)
3. [📡 Testing de API (Caja Negra)](#testing-de-api-caja-negra)
4. [🎭 Testing de Comportamiento (E2E)](#testing-de-comportamiento-e2e)
5. [🏭 Testing de Despliegue Continuo](#testing-de-despliegue-continuo)
6. [📊 Métricas y Análisis](#métricas-y-análisis)

---

## ⚙️ **CONFIGURACIÓN INICIAL DE PHPUNIT**

### **🔧 Instalación de PHPUnit**

PHPUnit ya viene incluido en Laravel, pero si necesitas instalarlo manualmente:

```bash
# Instalación via Composer (ya incluido en Laravel)
composer require --dev phpunit/phpunit

# Verificar versión instalada
vendor/bin/phpunit --version

# Instalación global (opcional)
composer global require phpunit/phpunit
```

### **📁 Estructura de Archivos de Testing**

```
tests/
├── TestCase.php          ← Clase base para todos los tests
├── CreatesApplication.php ← Trait para crear la aplicación
├── Unit/                 ← Tests unitarios (caja blanca)
│   ├── AlumnoTest.php
│   ├── GrupoTest.php
│   └── ...
└── Feature/              ← Tests de integración y E2E
    ├── API/
    ├── AlumnoIntegrationTest.php
    └── ...
```

### **🏗️ La Clase TestCase.php - El Corazón del Testing**

#### **¿Qué es TestCase.php?**

```php
<?php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Clase base abstracta para todos los tests
}
```

#### **🎯 Jerarquía de Herencia:**
```
PHPUnit\Framework\TestCase                    // ← PHPUnit base
    ↓
Illuminate\Foundation\Testing\TestCase        // ← Laravel testing tools  
    ↓
Tests\TestCase                                // ← Tu clase personalizada
    ↓
Tests\Unit\AlumnoTest extends Tests\TestCase  // ← Tus tests específicos
```

#### **🛠️ Funcionalidades que Proporciona:**

**1. Setup Automático de Laravel:**
- `refreshApplication()` - Reinicia la app entre tests
- `createApplication()` - Crea nueva instancia de la app
- Configuración automática del entorno de testing

**2. Acceso a Base de Datos de Testing:**
- Transacciones automáticas para aislar tests
- Métodos como `assertDatabaseHas()`, `assertDatabaseMissing()`
- Integration con factories y seeders

**3. Testing HTTP:**
- Métodos `get()`, `post()`, `put()`, `delete()`
- `actingAs($user)` para autenticación
- Verificación de respuestas JSON

#### **🎨 Personalización de TestCase (Ejemplos):**

```php
abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Configuración global para todos los tests
    }
    
    protected function createUser(): User
    {
        // Helper method para crear usuarios de prueba
        return User::factory()->create();
    }
    
    protected function authenticatedRequest($method, $uri, $data = [])
    {
        // Helper para requests autenticados
        return $this->actingAs($this->createUser())
                   ->$method($uri, $data);
    }
}
```

### **🚀 Comandos Básicos para Crear y Ejecutar Tests**

```bash
# Crear test unitario
php artisan make:test AlumnoTest --unit

# Crear test de feature/integración  
php artisan make:test AlumnoIntegrationTest

# Ejecutar todos los tests
vendor/bin/phpunit

# Ejecutar tests unitarios solamente
vendor/bin/phpunit tests/Unit/

# Ejecutar tests de feature solamente
vendor/bin/phpunit tests/Feature/

# Ejecutar test específico con filtro
vendor/bin/phpunit --filter nombre_del_test archivo_del_test.php

# Con cobertura de código (requiere Xdebug o PCOV instalado)
vendor/bin/phpunit --coverage-html coverage/

# Con información detallada de debug
vendor/bin/phpunit --debug

# Con documentación legible de tests
vendor/bin/phpunit --testdox

# Parar en el primer fallo
vendor/bin/phpunit --stop-on-failure
```

### **🎯 Comandos Específicos de Laravel para Testing**

```bash
# Ejecutar tests usando el comando de Laravel (alternativa a PHPUnit)
php artisan test

# Ejecutar tests con filtro usando Laravel
php artisan test --filter=AlumnoTest

# Ejecutar tests en paralelo (Laravel 8+)
php artisan test --parallel

# Preparar base de datos para testing
php artisan migrate:fresh --seed --env=testing

# Ejecutar solo migraciones para testing
php artisan migrate --env=testing

# Limpiar caché antes de testing
php artisan config:clear && php artisan cache:clear

# Ver comandos disponibles de testing
php artisan list | findstr test
```

### **⚠️ Sintaxis Correcta de PHPUnit 11+**

```bash
# ✅ CORRECTO - Usar --filter para tests específicos
vendor/bin/phpunit --filter nombre_del_test archivo_del_test.php

# ❌ INCORRECTO - La sintaxis :: no funciona en PHPUnit 11+
vendor/bin/phpunit archivo_del_test.php::nombre_del_test

# Ejemplos prácticos:
vendor/bin/phpunit --filter test_puede_crear_alumno tests/Unit/AlumnoTest.php
vendor/bin/phpunit --filter "test.*crear.*alumno" tests/Unit/AlumnoTest.php  # Con regex
```

### **� Configuración de Cobertura de Código**

Para generar reportes de cobertura de código, necesitas instalar **Xdebug** o **PCOV**:

#### **Instalación de Xdebug (Recomendado):**

```bash
# Verificar si Xdebug está instalado
php -m | findstr -i xdebug

# Si no está instalado, descargar desde: https://xdebug.org/download
# O usar XAMPP/Laragon que incluye Xdebug por defecto

# Verificar configuración en php.ini
php --ini
```

#### **Comandos de Cobertura (Solo con Xdebug instalado):**

```bash
# ✅ Con Xdebug instalado
vendor/bin/phpunit --coverage-text
vendor/bin/phpunit --coverage-html coverage/

# ❌ Sin Xdebug - Error: "No code coverage driver available"
# Instalar Xdebug primero
```

#### **Alternativas sin Xdebug:**

```bash
# Ejecutar tests sin cobertura
vendor/bin/phpunit

# Usar Laravel Test command
php artisan test

# Generar métricas básicas
vendor/bin/phpunit --testdox
```

### **�📝 Plantilla para Crear un Test Básico**

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TuModelo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TuModeloTest extends TestCase
{
    use RefreshDatabase; // Limpia BD entre tests
    
    public function test_puede_crear_modelo_con_datos_validos()
    {
        // Arrange - Preparar datos
        $datos = [
            'campo1' => 'valor1',
            'campo2' => 'valor2'
        ];
        
        // Act - Ejecutar acción
        $modelo = TuModelo::create($datos);
        
        // Assert - Verificar resultados
        $this->assertInstanceOf(TuModelo::class, $modelo);
        $this->assertEquals('valor1', $modelo->campo1);
        $this->assertDatabaseHas('tu_tabla', $datos);
    }
}
```

---

## 🔬 **TESTING UNITARIO (Caja Blanca)**

### **¿Qué es?**
- Prueba **componentes individuales** de forma aislada
- **Caja Blanca**: Conocemos la implementación interna del código
- Se enfocan en **métodos específicos** y **lógica de negocio**

### **¿Por qué "Caja Blanca"?**
Porque **conocemos** el código interno:
- ✅ Sabemos que existe `$fillable = ['legajo', 'nombre', 'email', 'grupo_id']`
- ✅ Conocemos los casts: `'legajo' => 'integer'`
- ✅ Sabemos que hay relación `belongsTo` con Grupo
- ✅ Entendemos las validaciones internas

### **Ejemplo Práctico:**

```php
// tests/Unit/AlumnoTest.php
public function test_atributos_son_casteados_correctamente()
{
    // CAJA BLANCA: Sabemos que hay casts definidos
    $alumno = Alumno::create([
        'legajo' => '99999',  // String → debe convertirse a integer
        'nombre' => 'Ana López',
        'email' => 'ana@example.com',
        'grupo_id' => '2'     // String → debe convertirse a integer
    ]);

    // Verificamos los tipos internos (conocemos la implementación)
    $this->assertIsInt($alumno->legajo);
    $this->assertIsInt($alumno->grupo_id);
}
```

### **Comandos para ejecutar:**

```bash
# Ejecutar solo tests unitarios
vendor/bin/phpunit tests/Unit/

# Con cobertura
vendor/bin/phpunit tests/Unit/ --coverage-text

# Test específico
vendor/bin/phpunit --filter test_puede_crear_alumno_con_datos_validos tests/Unit/AlumnoTest.php
```

---

## 🔗 **TESTING DE INTEGRACIÓN**

### **¿Qué es?**
- Prueba **interacción entre múltiples componentes**
- Verifica que **diferentes partes trabajen juntas**
- Incluye: Controladores + Modelos + Base de Datos + Middleware

### **¿Qué probamos?**
- ✅ HTTP Request → Controlador → Modelo → Base de Datos
- ✅ Validaciones de FormRequest
- ✅ Middleware de autenticación
- ✅ Respuestas JSON completas

### **Ejemplo Práctico:**

```php
// tests/Feature/AlumnoIntegrationTest.php
public function test_puede_crear_alumno_via_controlador()
{
    // INTEGRACIÓN: HTTP → Controlador → Modelo → BD
    $response = $this->actingAs($this->user)
                     ->postJson('/api/alumnos', $datosAlumno);

    // Verificamos respuesta HTTP
    $response->assertStatus(201);
    
    // Verificamos que se guardó en BD
    $this->assertDatabaseHas('alumnos', $datosAlumno);
    
    // Verificamos que el modelo se puede recuperar
    $alumno = Alumno::where('legajo', 88888)->first();
    $this->assertNotNull($alumno);
}
```

### **Comandos para ejecutar:**

```bash
# Ejecutar tests de integración
vendor/bin/phpunit tests/Feature/AlumnoIntegrationTest.php

# Con información de debug
vendor/bin/phpunit tests/Feature/AlumnoIntegrationTest.php --debug
```

---

## 📡 **TESTING DE API (Caja Negra)**

### **¿Qué es?**
- Prueba **endpoints REST** sin conocer implementación interna
- **Caja Negra**: Solo vemos entrada (Request) y salida (Response)
- No importa **cómo** funciona, importa **qué** devuelve

### **¿Por qué "Caja Negra"?**
Porque **NO conocemos** la implementación:
- ❌ No sabemos si usa Eloquent o Query Builder
- ❌ No sabemos qué validaciones tiene internamente
- ❌ No sabemos cómo se estructura la BD
- ✅ Solo sabemos: "POST /api/alumnos debe devolver 201 con JSON"

### **Ejemplo Práctico:**

```php
// tests/Feature/API/AlumnoApiTest.php
public function test_post_alumnos_retorna_201_con_estructura_correcta()
{
    // CAJA NEGRA: Solo definimos entrada y esperamos salida
    $payload = [
        'legajo' => 12345,
        'nombre' => 'API Test Student',
        'email' => 'api@test.com',
        'grupo_id' => 1
    ];

    // No sabemos cómo funciona internamente la API
    $response = $this->postJson('/api/alumnos', $payload);

    // Solo verificamos la salida esperada
    $response->assertStatus(201)
             ->assertJsonStructure(['id', 'legajo', 'nombre', 'email'])
             ->assertJson(['legajo' => 12345]);
}
```

### **Comparación Caja Blanca vs Caja Negra:**

| **Aspecto** | **Caja Blanca** | **Caja Negra** |
|-------------|-----------------|----------------|
| **Conocimiento** | Conocemos el código interno | Solo conocemos la interfaz |
| **Enfoque** | Estructura interna | Comportamiento externo |
| **Ejemplo** | "Verifico que el cast funciona" | "POST debe devolver 201" |
| **Cambios** | Se rompe si cambia implementación | Se mantiene si la interfaz es igual |

---

## 🎭 **TESTING DE COMPORTAMIENTO (E2E)**

### **¿Qué es?**
- Simula **flujos completos de usuario** desde inicio hasta fin
- Prueba **escenarios reales** de uso de la aplicación
- Incluye **múltiples pasos** y verificaciones de estado

### **¿Qué probamos?**
- ✅ Flujo completo: Login → Crear Grupo → Agregar Alumnos → Buscar → Actualizar
- ✅ Casos de uso reales del negocio
- ✅ Interacción entre diferentes funcionalidades
- ✅ Experiencia del usuario final

### **Ejemplo Práctico:**

```php
// tests/Feature/AlumnoBehaviorTest.php
public function test_flujo_completo_gestion_alumnos_como_admin()
{
    // PASO 1: Admin se autentica
    $this->actingAs($this->adminUser);

    // PASO 2: Admin crea grupos
    $grupoA = $this->postJson('/api/grupos', [...]);
    
    // PASO 3: Admin registra alumnos
    foreach ($alumnos as $alumnoData) {
        $this->postJson('/api/alumnos', $alumnoData);
    }
    
    // PASO 4: Admin consulta y verifica
    $this->getJson('/api/alumnos')->assertStatus(200);
    
    // PASO 5: Admin busca específicamente
    $this->getJson('/api/alumnos?search=Carlos');
    
    // ... más pasos del flujo completo
}
```

---

## 🏭 **TESTING DE DESPLIEGUE CONTINUO**

### **¿Qué es?**
- **Automatización** de tests en cada commit/push
- **GitHub Actions** ejecuta toda la suite de testing
- **Integración/Despliegue Continuo** (CI/CD)

### **¿Cómo funciona nuestro pipeline?**

```yaml
# .github/workflows/testing-suite.yml

# 1. Testing Unitario (Caja Blanca)
unit-tests:
  - Ejecuta tests/Unit/
  - Múltiples versiones de PHP (8.1, 8.2, 8.3)
  - Con cobertura de código

# 2. Testing de Integración  
integration-tests:
  - Ejecuta tests/Feature/AlumnoIntegrationTest.php
  - Base de datos MySQL real
  - Seeders con datos de prueba

# 3. Testing de API (Caja Negra)
api-tests:
  - Ejecuta tests/Feature/API/
  - Base de datos SQLite
  - Solo prueba endpoints

# 4. Testing E2E
e2e-tests:
  - Ejecuta tests/Feature/AlumnoBehaviorTest.php
  - Flujos completos de usuario

# 5. Análisis de Cobertura
coverage-analysis:
  - Genera reportes HTML
  - Sube a Codecov
  - Métricas de calidad
```

### **¿Cuándo se ejecuta?**
- ✅ En cada **push** a main/develop
- ✅ En cada **pull request**
- ✅ **Diariamente** a las 2 AM (programado)
- ✅ **Manualmente** cuando queramos

---

## 📊 **MÉTRICAS Y ANÁLISIS**

### **Comandos útiles para la presentación:**

```bash
# 1. Ejecutar TODOS los tests
vendor/bin/phpunit

# 2. Solo tests unitarios (Caja Blanca)
vendor/bin/phpunit tests/Unit/

# 3. Solo tests de API (Caja Negra) 
vendor/bin/phpunit tests/Feature/API/

# 4. Con cobertura de código
vendor/bin/phpunit --coverage-html coverage/

# 5. Test específico con información de debug
vendor/bin/phpunit --filter test_puede_crear_alumno_con_datos_validos tests/Unit/AlumnoTest.php --debug

# 6. Generar datos de prueba para desarrollo
php artisan db:seed
```

### **Estructura de archivos para mostrar:**

```
tests/
├── Unit/                          # 🔬 Caja Blanca
│   └── AlumnoTest.php
├── Feature/
│   ├── AlumnoIntegrationTest.php  # 🔗 Integración
│   ├── AlumnoBehaviorTest.php     # 🎭 E2E/Comportamiento
│   └── API/
│       └── AlumnoApiTest.php      # 📡 Caja Negra
├── database/
│   ├── factories/                 # 🏭 Generación de datos
│   └── seeders/                   # 🌱 Datos de prueba
└── .github/workflows/
    └── testing-suite.yml          # ⚙️ CI/CD
```

---

## 🎯 **PUNTOS CLAVE PARA LA PRESENTACIÓN**

### **1. Diferencias principales:**

| **Tipo** | **Enfoque** | **Conocimiento** | **Objetivo** |
|----------|-------------|------------------|--------------|
| **Unitario (Caja Blanca)** | Componente aislado | Conoce implementación | Verificar lógica interna |
| **Integración** | Múltiples componentes | Conoce arquitectura | Verificar interacciones |
| **API (Caja Negra)** | Interfaz externa | Solo conoce contratos | Verificar comportamiento |
| **E2E** | Flujo completo | Conoce casos de uso | Verificar experiencia |

### **2. Beneficios del testing:**
- ✅ **Detección temprana** de errores
- ✅ **Confianza** en los cambios
- ✅ **Documentación viva** del comportamiento
- ✅ **Facilitación** del refactoring
- ✅ **Calidad** del software

### **3. Cuándo usar cada tipo:**
- **Unitario**: Lógica de negocio compleja, algoritmos, validaciones
- **Integración**: Controladores, servicios, interacciones BD
- **API**: Contratos públicos, APIs REST, integraciones externas
- **E2E**: Flujos críticos de usuario, casos de negocio completos

---

## 🚀 **PRESENTACIÓN**

### **Secuencia sugerida:**

1. **📋 Mostrar la estructura** de archivos de testing
2. **🔬 Ejecutar test unitario** y explicar "caja blanca"
3. **📡 Ejecutar test API** y explicar "caja negra"
4. **🎭 Mostrar test E2E** y explicar flujo completo
5. **⚙️ Mostrar GitHub Actions** en funcionamiento
6. **📊 Mostrar reportes** de cobertura

### **Comandos para la demo:**

```bash
# Limpiar y preparar base de datos para testing
php artisan migrate:fresh --seed --env=testing

# Demo 1: Test unitario específico
vendor/bin/phpunit --filter test_atributos_son_casteados_correctamente tests/Unit/AlumnoTest.php

# Demo 2: Test API específico  
vendor/bin/phpunit --filter test_post_alumnos_comportamiento_actual tests/Feature/API/AlumnoApiTest.php

# Demo 3: Tests que funcionan perfectamente (Unitarios + API)
vendor/bin/phpunit tests/Unit/ tests/Feature/API/ --testdox

# Demo 4: Tests funcionales con documentación legible (100% éxito)
vendor/bin/phpunit tests/Unit/ tests/Feature/API/ --testdox

# Demo 5: Solo si tienes Xdebug instalado
vendor/bin/phpunit --coverage-text
```

### **⚠️ Notas importantes:**

**Sobre Cobertura:**
- **Sin Xdebug**: Usar `--testdox` para documentación legible
- **Con Xdebug**: Usar `--coverage-text` o `--coverage-html`

**Sobre Tests para Presentación:**
- **Recomendado**: `vendor/bin/phpunit tests/Unit/ tests/Feature/API/ --testdox` (15 tests, 100% éxito)
- **Evitar**: `vendor/bin/phpunit --testdox` (26 tests, incluye fallos en integración/comportamiento)

**¿Por qué algunos tests fallan?**
Los tests de **Integración** y **Comportamiento** (E2E) fallan porque:
- Dependen de endpoints que no están implementados (404 errors)
- Tienen errores en la lógica de filtros (`assertCount` con null)
- Usan status codes diferentes a los esperados (200 vs 204)

**Para la presentación, usa los tests que funcionan al 100%:** Unitarios (Caja Blanca) + API (Caja Negra)

---

## 📚 **RECURSOS ADICIONALES**

- **Laravel Testing**: https://laravel.com/docs/testing
- **PHPUnit**: https://phpunit.de/documentation.html
- **GitHub Actions**: https://docs.github.com/en/actions

