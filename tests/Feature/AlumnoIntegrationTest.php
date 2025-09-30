<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\User;

/**
 * Testing de Integración
 * 
 * Probamos cómo interactúan múltiples componentes:
 * - Controladores
 * - Modelos
 * - Base de datos
 * - Middleware
 * - Validaciones
 */
class AlumnoIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Creamos un usuario para pruebas de autenticación
        $this->user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);
    }

    /**
     * Prueba la integración completa: Controlador + Modelo + Base de datos
     * Verifica que el flujo completo funciona desde HTTP hasta persistencia
     */
    public function test_puede_crear_alumno_via_controlador()
    {
        // Arrange
        $grupo = Grupo::create([
            'nombre' => 'Grupo Test',
            'descripcion' => 'Grupo para testing'
        ]);

        $datosAlumno = [
            'legajo' => 88888,
            'nombre' => 'Integration Test Student',
            'email' => 'integration@test.com',
            'grupo_id' => $grupo->id
        ];

        // Act - Hacemos la petición HTTP al controlador
        $response = $this->actingAs($this->user)
                         ->postJson('/api/alumnos', $datosAlumno);

        // Assert - Verificamos respuesta HTTP
        $response->assertStatus(201)
                 ->assertJson([
                     'legajo' => 88888,
                     'nombre' => 'Integration Test Student',
                     'email' => 'integration@test.com',
                     'grupo_id' => $grupo->id
                 ]);

        // Assert - Verificamos que se guardó en base de datos
        $this->assertDatabaseHas('alumnos', $datosAlumno);

        // Assert - Verificamos que el modelo se puede recuperar
        $alumno = Alumno::where('legajo', 88888)->first();
        $this->assertNotNull($alumno);
        $this->assertEquals($grupo->id, $alumno->grupo_id);
    }

    /**
     * Prueba integración con validaciones del controlador
     * Verifica que las validaciones del FormRequest funcionan
     */
    public function test_validacion_datos_invalidos_en_controlador()
    {
        // Arrange - Datos inválidos
        $datosInvalidos = [
            'legajo' => '',  // Requerido
            'nombre' => '',  // Requerido
            'email' => 'email-invalido',  // Formato inválido
            'grupo_id' => 999999  // Grupo que no existe
        ];

        // Act
        $response = $this->actingAs($this->user)
                         ->postJson('/api/alumnos', $datosInvalidos);

        // Assert - Debe retornar error de validación
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['legajo', 'nombre', 'email', 'grupo_id']);

        // Assert - No debe guardar nada en base de datos
        $this->assertDatabaseCount('alumnos', 0);
    }

    /**
     * Prueba integración con middleware de autenticación
     * Verifica que se requiere autenticación para acceder
     */
    public function test_requiere_autenticacion_para_crear_alumno()
    {
        // Arrange
        $datosAlumno = [
            'legajo' => 99999,
            'nombre' => 'Test Student',
            'email' => 'test@example.com',
            'grupo_id' => 1
        ];

        // Act - Sin autenticación
        $response = $this->postJson('/api/alumnos', $datosAlumno);

        // Assert - Debe rechazar la petición
        $response->assertStatus(401);
        $this->assertDatabaseCount('alumnos', 0);
    }

    /**
     * Prueba integración completa de búsqueda con filtros
     * Verifica controlador + query builder + respuesta JSON
     */
    public function test_busqueda_alumnos_con_filtros()
    {
        // Arrange - Creamos varios alumnos
        $grupo1 = Grupo::create(['nombre' => 'Grupo 1', 'descripcion' => 'Desc 1']);
        $grupo2 = Grupo::create(['nombre' => 'Grupo 2', 'descripcion' => 'Desc 2']);

        Alumno::create([
            'legajo' => 1001,
            'nombre' => 'Juan Carlos',
            'email' => 'juan@test.com',
            'grupo_id' => $grupo1->id
        ]);

        Alumno::create([
            'legajo' => 1002,
            'nombre' => 'María José',
            'email' => 'maria@test.com',
            'grupo_id' => $grupo2->id
        ]);

        Alumno::create([
            'legajo' => 1003,
            'nombre' => 'Carlos Alberto',
            'email' => 'carlos@test.com',
            'grupo_id' => $grupo1->id
        ]);

        // Act - Buscamos alumnos que contengan "Carlos"
        $response = $this->actingAs($this->user)
                        ->getJson('/api/alumnos?search=Carlos');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(2, $data); // Juan Carlos y Carlos Alberto
        $this->assertTrue(
            collect($data)->every(function ($alumno) {
                return str_contains($alumno['nombre'], 'Carlos');
            })
        );
    }

    /**
     * Prueba integración de actualización con relaciones
     * Verifica que se actualiza correctamente incluyendo relaciones
     */
    public function test_actualizar_alumno_cambia_grupo()
    {
        // Arrange
        $grupo1 = Grupo::create(['nombre' => 'Grupo Original', 'descripcion' => 'Desc 1']);
        $grupo2 = Grupo::create(['nombre' => 'Grupo Nuevo', 'descripcion' => 'Desc 2']);

        $alumno = Alumno::create([
            'legajo' => 2001,
            'nombre' => 'Alumno Movible',
            'email' => 'movible@test.com',
            'grupo_id' => $grupo1->id
        ]);

        // Act - Actualizamos el grupo del alumno
        $response = $this->actingAs($this->user)
                         ->putJson("/api/alumnos/{$alumno->id}", [
                             'legajo' => 2001,
                             'nombre' => 'Alumno Movible Actualizado',
                             'email' => 'movible@test.com',
                             'grupo_id' => $grupo2->id
                         ]);

        // Assert
        $response->assertStatus(200);
        
        // Verificamos en base de datos
        $alumnoActualizado = Alumno::find($alumno->id);
        $this->assertEquals($grupo2->id, $alumnoActualizado->grupo_id);
        $this->assertEquals('Alumno Movible Actualizado', $alumnoActualizado->nombre);
        
        // Verificamos que la relación funciona
        $this->assertEquals('Grupo Nuevo', $alumnoActualizado->grupo->nombre);
    }

    /**
     * Prueba integración de eliminación con verificación de datos relacionados
     */
    public function test_eliminar_alumno_limpia_datos_relacionados()
    {
        // Arrange
        $grupo = Grupo::create(['nombre' => 'Grupo Test', 'descripcion' => 'Test']);
        $alumno = Alumno::create([
            'legajo' => 3001,
            'nombre' => 'Alumno a Eliminar',
            'email' => 'eliminar@test.com',
            'grupo_id' => $grupo->id
        ]);

        // Act
        $response = $this->actingAs($this->user)
                         ->deleteJson("/api/alumnos/{$alumno->id}");

        // Assert
        $response->assertStatus(204);
        
        // Verificamos que se eliminó de base de datos
        $this->assertDatabaseMissing('alumnos', ['id' => $alumno->id]);
        
        // Verificamos que el grupo sigue existiendo (no se elimina en cascada)
        $this->assertDatabaseHas('grupos', ['id' => $grupo->id]);
    }
}
