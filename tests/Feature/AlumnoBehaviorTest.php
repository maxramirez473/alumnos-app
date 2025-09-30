<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Grupo;
use App\Models\Alumno;

/**
 * Testing de Comportamiento (End-to-End / E2E)
 * 
 * Simula flujos completos de usuario desde inicio hasta fin
 * Incluye múltiples pasos y verificaciones de estado
 * Prueba escenarios reales de uso de la aplicación
 */
class AlumnoBehaviorTest extends TestCase
{
    use RefreshDatabase;

    private $adminUser;
    private $teacherUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Creamos usuarios con diferentes roles
        $this->adminUser = User::factory()->create([
            'name' => 'Admin Usuario',
            'email' => 'admin@school.com',
            'password' => bcrypt('admin123')
        ]);

        $this->teacherUser = User::factory()->create([
            'name' => 'Profesor Usuario', 
            'email' => 'teacher@school.com',
            'password' => bcrypt('teacher123')
        ]);
    }

    /**
     * E2E: Flujo completo de gestión de alumnos por un administrador
     * 
     * Historia de usuario:
     * "Como administrador, quiero gestionar alumnos completos 
     *  para organizar mi institución educativa"
     */
    public function test_flujo_completo_gestion_alumnos_como_admin()
    {
        // PASO 1: Admin se autentica
        $this->actingAs($this->adminUser);

        // PASO 2: Admin crea grupos para organizar alumnos
        $grupoA = $this->postJson('/api/grupos', [
            'nombre' => 'Grupo A - Matemáticas',
            'descripcion' => 'Grupo avanzado de matemáticas'
        ])->assertStatus(201);

        $grupoB = $this->postJson('/api/grupos', [
            'nombre' => 'Grupo B - Historia', 
            'descripcion' => 'Grupo de historia general'
        ])->assertStatus(201);

        $grupoAId = $grupoA->json('id');
        $grupoBId = $grupoB->json('id');

        // PASO 3: Admin registra varios alumnos en diferentes grupos
        $alumnos = [
            [
                'legajo' => 2001,
                'nombre' => 'Ana Martínez',
                'email' => 'ana.martinez@estudiantes.com',
                'grupo_id' => $grupoAId
            ],
            [
                'legajo' => 2002,
                'nombre' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@estudiantes.com', 
                'grupo_id' => $grupoAId
            ],
            [
                'legajo' => 2003,
                'nombre' => 'Lucía Fernández',
                'email' => 'lucia.fernandez@estudiantes.com',
                'grupo_id' => $grupoBId
            ]
        ];

        $alumnosCreados = [];
        foreach ($alumnos as $alumnoData) {
            $response = $this->postJson('/api/alumnos', $alumnoData);
            $response->assertStatus(201);
            $alumnosCreados[] = $response->json();
        }

        // PASO 4: Admin consulta lista de alumnos y verifica que estén todos
        $listaResponse = $this->getJson('/api/alumnos');
        $listaResponse->assertStatus(200);
        
        $listaData = $listaResponse->json('data');
        $this->assertCount(3, $listaData);

        // PASO 5: Admin busca alumnos por nombre
        $busquedaResponse = $this->getJson('/api/alumnos?search=Carlos');
        $busquedaResponse->assertStatus(200);
        
        $resultadosBusqueda = $busquedaResponse->json('data');
        $this->assertCount(1, $resultadosBusqueda);
        $this->assertEquals('Carlos Rodríguez', $resultadosBusqueda[0]['nombre']);

        // PASO 6: Admin actualiza información de un alumno
        $carlosId = $alumnosCreados[1]['id'];
        $actualizacionResponse = $this->putJson("/api/alumnos/{$carlosId}", [
            'legajo' => 2002,
            'nombre' => 'Carlos Rodríguez Silva',
            'email' => 'carlos.rodriguez.silva@estudiantes.com',
            'grupo_id' => $grupoBId  // Lo cambia de grupo
        ]);
        $actualizacionResponse->assertStatus(200);

        // PASO 7: Admin verifica que el cambio se aplicó correctamente
        $verificacionResponse = $this->getJson("/api/alumnos/{$carlosId}");
        $verificacionResponse->assertStatus(200)
                           ->assertJson([
                               'nombre' => 'Carlos Rodríguez Silva',
                               'grupo_id' => $grupoBId
                           ]);

        // PASO 8: Admin consulta alumnos por grupo
        $grupoAResponse = $this->getJson("/api/grupos/{$grupoAId}/alumnos");
        $grupoAResponse->assertStatus(200);
        
        $alumnosGrupoA = $grupoAResponse->json('data');
        $this->assertCount(1, $alumnosGrupoA); // Solo Ana quedó en Grupo A
        $this->assertEquals('Ana Martínez', $alumnosGrupoA[0]['nombre']);

        // PASO 9: Admin decide eliminar un alumno que se dio de baja
        $luciaId = $alumnosCreados[2]['id'];
        $eliminacionResponse = $this->deleteJson("/api/alumnos/{$luciaId}");
        $eliminacionResponse->assertStatus(204);

        // PASO 10: Admin verifica que el alumno fue eliminado
        $verificacionEliminacion = $this->getJson("/api/alumnos/{$luciaId}");
        $verificacionEliminacion->assertStatus(404);

        // PASO 11: Admin hace consulta final para confirmar estado
        $consultaFinal = $this->getJson('/api/alumnos');
        $consultaFinal->assertStatus(200);
        
        $estadoFinal = $consultaFinal->json('data');
        $this->assertCount(2, $estadoFinal); // Ana y Carlos
        
        // Verificamos que los nombres son correctos
        $nombres = array_column($estadoFinal, 'nombre');
        $this->assertContains('Ana Martínez', $nombres);
        $this->assertContains('Carlos Rodríguez Silva', $nombres);
    }

    /**
     * E2E: Flujo de inscripción masiva de alumnos
     * 
     * Historia de usuario:
     * "Como administrador, quiero poder inscribir múltiples alumnos 
     *  de forma eficiente al inicio del período académico"
     */
    public function test_flujo_inscripcion_masiva_inicio_semestre()
    {
        $this->actingAs($this->adminUser);

        // PASO 1: Preparación - Crear múltiples grupos
        $grupos = [];
        for ($i = 1; $i <= 3; $i++) {
            $response = $this->postJson('/api/grupos', [
                'nombre' => "Curso {$i}° Año",
                'descripcion' => "Estudiantes de {$i}° año"
            ]);
            $grupos[] = $response->json('id');
        }

        // PASO 2: Inscripción masiva - Simular carga de 50 alumnos
        $alumnosInscritos = [];
        for ($i = 1; $i <= 50; $i++) {
            $grupoId = $grupos[($i - 1) % 3]; // Distribuir en 3 grupos
            
            $response = $this->postJson('/api/alumnos', [
                'legajo' => 3000 + $i,
                'nombre' => "Estudiante {$i}",
                'email' => "estudiante{$i}@colegio.com",
                'grupo_id' => $grupoId
            ]);
            
            $response->assertStatus(201);
            $alumnosInscritos[] = $response->json();
        }

        // PASO 3: Verificación de inscripción completa
        $totalResponse = $this->getJson('/api/alumnos');
        $totalResponse->assertStatus(200);
        
        $totalAlumnos = $totalResponse->json('meta.total');
        $this->assertEquals(50, $totalAlumnos);

        // PASO 4: Verificación de distribución por grupos
        foreach ($grupos as $index => $grupoId) {
            $grupoResponse = $this->getJson("/api/grupos/{$grupoId}/alumnos");
            $grupoResponse->assertStatus(200);
            
            $alumnosEnGrupo = count($grupoResponse->json('data'));
            $this->assertGreaterThan(15, $alumnosEnGrupo); // Distribución equilibrada
            $this->assertLessThan(20, $alumnosEnGrupo);
        }

        // PASO 5: Búsqueda y verificación de datos específicos
        $busquedaResponse = $this->getJson('/api/alumnos?search=Estudiante 25');
        $busquedaResponse->assertStatus(200);
        
        $resultado = $busquedaResponse->json('data');
        $this->assertCount(1, $resultado);
        $this->assertEquals(3025, $resultado[0]['legajo']);
    }

    /**
     * E2E: Flujo de manejo de errores y recuperación
     * 
     * Historia de usuario:
     * "Como usuario del sistema, cuando cometo errores, 
     *  quiero recibir mensajes claros y poder corregirlos fácilmente"
     */
    public function test_flujo_manejo_errores_y_recuperacion()
    {
        $this->actingAs($this->adminUser);

        // PASO 1: Crear grupo válido primero
        $grupoResponse = $this->postJson('/api/grupos', [
            'nombre' => 'Grupo Válido',
            'descripcion' => 'Grupo para testing de errores'
        ]);
        $grupoValido = $grupoResponse->json('id');

        // PASO 2: Intentar crear alumno con datos inválidos
        $datosInvalidos = [
            'legajo' => '', // Vacío
            'nombre' => '',
            'email' => 'email-malo',
            'grupo_id' => 99999 // Grupo inexistente
        ];

        $errorResponse = $this->postJson('/api/alumnos', $datosInvalidos);
        $errorResponse->assertStatus(422)
                     ->assertJsonValidationErrors(['legajo', 'nombre', 'email', 'grupo_id']);

        // PASO 3: Usuario corrige errores uno por uno
        
        // Corrige legajo
        $datosCorreccion1 = array_merge($datosInvalidos, ['legajo' => 4001]);
        $response1 = $this->postJson('/api/alumnos', $datosCorreccion1);
        $response1->assertStatus(422)
                  ->assertJsonMissingValidationErrors(['legajo'])
                  ->assertJsonValidationErrors(['nombre', 'email', 'grupo_id']);

        // Corrige nombre
        $datosCorreccion2 = array_merge($datosCorreccion1, ['nombre' => 'Alumno Corregido']);
        $response2 = $this->postJson('/api/alumnos', $datosCorreccion2);
        $response2->assertStatus(422)
                  ->assertJsonMissingValidationErrors(['legajo', 'nombre'])
                  ->assertJsonValidationErrors(['email', 'grupo_id']);

        // Corrige email
        $datosCorreccion3 = array_merge($datosCorreccion2, ['email' => 'correcto@email.com']);
        $response3 = $this->postJson('/api/alumnos', $datosCorreccion3);
        $response3->assertStatus(422)
                  ->assertJsonMissingValidationErrors(['legajo', 'nombre', 'email'])
                  ->assertJsonValidationErrors(['grupo_id']);

        // PASO 4: Corrige último error y crea exitosamente
        $datosCorrectos = array_merge($datosCorreccion3, ['grupo_id' => $grupoValido]);
        $successResponse = $this->postJson('/api/alumnos', $datosCorrectos);
        $successResponse->assertStatus(201)
                       ->assertJson([
                           'legajo' => 4001,
                           'nombre' => 'Alumno Corregido',
                           'email' => 'correcto@email.com',
                           'grupo_id' => $grupoValido
                       ]);

        // PASO 5: Verifica que el alumno se creó correctamente
        $alumnoId = $successResponse->json('id');
        $verificacion = $this->getJson("/api/alumnos/{$alumnoId}");
        $verificacion->assertStatus(200);
    }

    /**
     * E2E: Flujo de trabajo colaborativo entre admin y profesor
     * 
     * Historia de usuario:
     * "Como profesor, quiero poder consultar información de mis alumnos
     *  mientras el administrador puede modificar la información"
     */
    public function test_flujo_colaborativo_admin_profesor()
    {
        // PASO 1: Admin crea estructura inicial
        $this->actingAs($this->adminUser);

        $grupoResponse = $this->postJson('/api/grupos', [
            'nombre' => 'Matemáticas Avanzadas',
            'descripcion' => 'Curso de matemáticas para nivel avanzado'
        ]);
        $grupoId = $grupoResponse->json('id');

        $alumnoResponse = $this->postJson('/api/alumnos', [
            'legajo' => 5001,
            'nombre' => 'Estudiante Colaborativo',
            'email' => 'colaborativo@test.com',
            'grupo_id' => $grupoId
        ]);
        $alumnoId = $alumnoResponse->json('id');

        // PASO 2: Profesor consulta información (solo lectura)
        $this->actingAs($this->teacherUser);

        $consultaProfesor = $this->getJson("/api/alumnos/{$alumnoId}");
        $consultaProfesor->assertStatus(200)
                        ->assertJson([
                            'legajo' => 5001,
                            'nombre' => 'Estudiante Colaborativo'
                        ]);

        // PASO 3: Profesor consulta lista de su grupo
        $grupoProfesor = $this->getJson("/api/grupos/{$grupoId}/alumnos");
        $grupoProfesor->assertStatus(200);
        
        $alumnosDelGrupo = $grupoProfesor->json('data');
        $this->assertCount(1, $alumnosDelGrupo);

        // PASO 4: Admin hace cambios mientras profesor está trabajando
        $this->actingAs($this->adminUser);

        $cambioAdmin = $this->putJson("/api/alumnos/{$alumnoId}", [
            'legajo' => 5001,
            'nombre' => 'Estudiante Colaborativo Actualizado',
            'email' => 'colaborativo.actualizado@test.com',
            'grupo_id' => $grupoId
        ]);
        $cambioAdmin->assertStatus(200);

        // PASO 5: Profesor ve los cambios inmediatamente
        $this->actingAs($this->teacherUser);

        $consultaActualizada = $this->getJson("/api/alumnos/{$alumnoId}");
        $consultaActualizada->assertStatus(200)
                           ->assertJson([
                               'nombre' => 'Estudiante Colaborativo Actualizado',
                               'email' => 'colaborativo.actualizado@test.com'
                           ]);

        // PASO 6: Verificación final de consistencia
        $this->actingAs($this->adminUser);
        
        $verificacionFinal = $this->getJson("/api/alumnos/{$alumnoId}");
        $verificacionFinal->assertStatus(200)
                         ->assertJson([
                             'nombre' => 'Estudiante Colaborativo Actualizado'
                         ]);
    }
}
