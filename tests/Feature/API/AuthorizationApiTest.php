<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Grupo;

/**
 * Testing de Autorización de API - Seguridad por Roles
 * 
 * Este test documenta y verifica las políticas de autorización
 * en las rutas API, específicamente para operaciones de eliminación
 * que requieren permisos especiales.
 * 
 * ⚠️ HALLAZGOS DE SEGURIDAD:
 * Las rutas web tienen middleware 'admin' pero las rutas API solo tienen 'auth:sanctum'
 * Esto crea inconsistencia en las políticas de seguridad.
 */
class AuthorizationApiTest extends TestCase
{
    use RefreshDatabase;

    private $adminHeaders;
    private $userHeaders;
    private $testGrupo;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar headers base
        $baseHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // Crear usuario ADMIN y token
        $adminUser = User::factory()->create(['rol' => 'admin']);
        $adminToken = $adminUser->createToken('admin-test')->plainTextToken;
        $this->adminHeaders = array_merge($baseHeaders, [
            'Authorization' => "Bearer {$adminToken}"
        ]);

        // Crear usuario REGULAR y token
        $regularUser = User::factory()->create(['rol' => 'user']);
        $userToken = $regularUser->createToken('user-test')->plainTextToken;
        $this->userHeaders = array_merge($baseHeaders, [
            'Authorization' => "Bearer {$userToken}"
        ]);

        // Crear grupo de prueba
        $this->testGrupo = Grupo::create([
            'nombre' => 'Grupo Autorización',
            'numero' => 1
        ]);
    }

    /**
     * 🔐 Test: Admin puede eliminar alumnos vía API
     * Verifica que usuarios con rol 'admin' pueden usar DELETE /api/alumnos/{id}
     */
    public function test_admin_puede_eliminar_alumno_via_api()
    {
        // Arrange
        $alumno = Alumno::create([
            'legajo' => 8001,
            'nombre' => 'Alumno Admin Test',
            'email' => 'admin.test@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        // Act - Admin elimina vía API
        $response = $this->deleteJson("/api/alumnos/{$alumno->id}", [], $this->adminHeaders);

        // Assert
        $response->assertStatus(204);
        $this->assertDatabaseMissing('alumnos', ['id' => $alumno->id]);
    }

    /**
     * 🚨 Test: Usuario regular PUEDE eliminar vía API (PROBLEMA DE SEGURIDAD)
     * 
     * Este test documenta un problema de seguridad actual:
     * Las rutas API no verifican roles, solo autenticación.
     * 
     * COMPORTAMIENTO ACTUAL: ❌ Usuario regular puede eliminar
     * COMPORTAMIENTO ESPERADO: ✅ Usuario regular debería recibir 403 Forbidden
     */
    public function test_usuario_regular_puede_eliminar_via_api_problema_seguridad()
    {
        // Arrange
        $alumno = Alumno::create([
            'legajo' => 9001,
            'nombre' => 'Alumno User Test',
            'email' => 'user.test@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        // Act - Usuario regular intenta eliminar vía API
        $response = $this->deleteJson("/api/alumnos/{$alumno->id}", [], $this->userHeaders);

        // Assert - PROBLEMA: El usuario regular PUEDE eliminar
        $response->assertStatus(204); // ❌ Debería ser 403, pero es 204
        $this->assertDatabaseMissing('alumnos', ['id' => $alumno->id]);
        
        // TODO: Agregar middleware 'admin' a rutas API críticas
        // Ejemplo de cómo debería ser:
        // Route::delete('alumnos/{id}', [AlumnoController::class, 'destroy'])
        //      ->middleware(['auth:sanctum', 'admin']);
    }

    /**
     * 🔐 Test: Comparación Web vs API - Inconsistencia de Seguridad
     * 
     * Demuestra la diferencia de comportamiento entre rutas web y API
     * para la misma operación (eliminar alumno)
     */
    public function test_inconsistencia_web_vs_api_autorizacion()
    {
        // Arrange - Crear dos alumnos idénticos
        $alumnoWeb = Alumno::create([
            'legajo' => 10001,
            'nombre' => 'Alumno Web Test',
            'email' => 'web.test@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        $alumnoApi = Alumno::create([
            'legajo' => 10002,
            'nombre' => 'Alumno API Test',
            'email' => 'api.test@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        // Crear usuario regular
        $regularUser = User::factory()->create(['rol' => 'user']);

        // Act & Assert - Ruta WEB (con middleware admin)
        $webResponse = $this->actingAs($regularUser)
                        ->delete("/alumnos/{$alumnoWeb->id}");
        
        $webResponse->assertStatus(403); // ✅ Web bloquea correctamente
        $this->assertDatabaseHas('alumnos', ['id' => $alumnoWeb->id]); // ✅ Alumno sigue existiendo

        // Act & Assert - Ruta API (sin middleware admin)
        $apiResponse = $this->deleteJson("/api/alumnos/{$alumnoApi->id}", [], $this->userHeaders);
        
        $apiResponse->assertStatus(204); // ❌ API permite eliminación
        $this->assertDatabaseMissing('alumnos', ['id' => $alumnoApi->id]); // ❌ Alumno eliminado

        // CONCLUSIÓN: Hay inconsistencia de seguridad entre web y API
    }

    /**
     * 🚫 Test: Usuario sin token no puede acceder a la API
     * Verifica que la autenticación básica sí funciona en API
     */
    public function test_sin_token_no_puede_eliminar_via_api()
    {
        // Arrange
        $alumno = Alumno::create([
            'legajo' => 11001,
            'nombre' => 'Alumno Sin Auth',
            'email' => 'sinauth.api@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        // Act - Sin token de autorización
        $response = $this->deleteJson("/api/alumnos/{$alumno->id}", [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            // Sin Authorization header
        ]);

        // Assert - Debe rechazar por falta de autenticación
        $response->assertStatus(401);
        $this->assertDatabaseHas('alumnos', ['id' => $alumno->id]);
    }

    /**
     * 🔐 Test: Token inválido no puede eliminar
     * Verifica validación de tokens
     */
    public function test_token_invalido_no_puede_eliminar_via_api()
    {
        // Arrange
        $alumno = Alumno::create([
            'legajo' => 12001,
            'nombre' => 'Alumno Token Inválido',
            'email' => 'invalidtoken@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        // Act - Con token inválido
        $response = $this->deleteJson("/api/alumnos/{$alumno->id}", [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token-completamente-inventado'
        ]);

        // Assert
        $response->assertStatus(401);
        $this->assertDatabaseHas('alumnos', ['id' => $alumno->id]);
    }

    /**
     * 🔍 Test: Operaciones de lectura están disponibles para todos los usuarios autenticados
     * Verifica que las operaciones READ no requieren permisos especiales
     */
    public function test_operaciones_lectura_disponibles_para_usuarios_regulares()
    {
        // Arrange
        $alumno = Alumno::create([
            'legajo' => 13001,
            'nombre' => 'Alumno Lectura',
            'email' => 'lectura@example.com',
            'grupo_id' => $this->testGrupo->id
        ]);

        // Act & Assert - Usuario regular puede leer lista
        $listResponse = $this->getJson('/api/alumnos', $this->userHeaders);
        $listResponse->assertStatus(200);

        // Act & Assert - Usuario regular puede leer individualmente
        $showResponse = $this->getJson("/api/alumnos/{$alumno->id}", $this->userHeaders);
        $showResponse->assertStatus(200)
                    ->assertJson([
                        'id' => $alumno->id,
                        'legajo' => 13001,
                        'nombre' => 'Alumno Lectura'
                    ]);

        // Las operaciones de lectura están correctamente disponibles
    }

    /**
     * 📝 Test: Operaciones de escritura (CREATE/UPDATE) - Estado actual
     * Documenta qué operaciones están disponibles para usuarios regulares
     */
    public function test_operaciones_escritura_estado_actual()
    {
        // CREATE - Usuario regular puede crear alumnos
        $createResponse = $this->postJson('/api/alumnos', [
            'legajo' => 14001,
            'nombre' => 'Nuevo Alumno Regular',
            'email' => 'nuevo.regular@example.com',
            'grupo_id' => $this->testGrupo->id
        ], $this->userHeaders);

        $createResponse->assertStatus(201); // ✅ Crear permitido

        $alumnoId = $createResponse->json('id');

        // UPDATE - Usuario regular puede actualizar alumnos
        $updateResponse = $this->putJson("/api/alumnos/{$alumnoId}", [
            'legajo' => 14001,
            'nombre' => 'Alumno Regular Actualizado',
            'email' => 'actualizado.regular@example.com',
            'grupo_id' => $this->testGrupo->id
        ], $this->userHeaders);

        $updateResponse->assertStatus(200); // ✅ Actualizar permitido

        // DELETE - Usuario regular puede eliminar (PROBLEMA IDENTIFICADO)
        $deleteResponse = $this->deleteJson("/api/alumnos/{$alumnoId}", [], $this->userHeaders);
        $deleteResponse->assertStatus(204); // ❌ Eliminar permitido (debería estar restringido)
    }
}
