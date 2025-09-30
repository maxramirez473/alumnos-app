<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

/**
 * Testing de API - Caja Negra
 * 
 * Probamos la API REST sin conocer la implementación interna
 * Solo verificamos:
 * - Entrada (Request)
 * - Salida (Response) 
 * - Códigos de estado HTTP
 * - Estructura JSON
 * - Headers
 * 
 * NO sabemos cómo funciona internamente el código
 */
class AlumnoApiTest extends TestCase
{
    use RefreshDatabase;

    private $headers;
    private $baseUrl = '/api/alumnos';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configuramos headers para API
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // Creamos usuario y obtenemos token de autenticación
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        $this->headers['Authorization'] = "Bearer {$token}";
    }

    /**
     * CAJA NEGRA: Solo verificamos que POST /api/alumnos
     * - Acepta JSON válido
     * - Retorna 201 Created
     * - Retorna estructura JSON esperada
     */
    public function test_post_alumnos_retorna_201_con_estructura_correcta()
    {
        // Arrange - Input
        $payload = [
            'legajo' => 12345,
            'nombre' => 'API Test Student',
            'email' => 'api@test.com',
            'grupo_id' => 1
        ];

        // Act - Llamada a la API
        $response = $this->postJson($this->baseUrl, $payload, $this->headers);

        // Assert - Output (Caja Negra)
        $response->assertStatus(201)
                 ->assertHeader('content-type', 'application/json')
                 ->assertJsonStructure([
                     'id',
                     'legajo',
                     'nombre', 
                     'email',
                     'grupo_id'
                 ])
                 ->assertJson([
                     'legajo' => 12345,
                     'nombre' => 'API Test Student',
                     'email' => 'api@test.com',
                     'grupo_id' => 1
                 ]);
    }

    /**
     * CAJA NEGRA: Verificamos que GET /api/alumnos
     * - Retorna 200 OK
     * - Retorna array de alumnos
     * - Tiene paginación
     */
    public function test_get_alumnos_retorna_lista_paginada()
    {
        // Act
        $response = $this->getJson($this->baseUrl, $this->headers);

        // Assert - Solo verificamos estructura de salida
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'legajo',
                             'nombre',
                             'email',
                             'grupo_id'
                         ]
                     ],
                     'links' => [
                         'first',
                         'last',
                         'prev',
                         'next'
                     ],
                     'meta' => [
                         'current_page',
                         'last_page',
                         'per_page',
                         'total'
                     ]
                 ]);
    }

    /**
     * CAJA NEGRA: Verificamos que GET /api/alumnos/{id}
     * - Con ID existente retorna 200
     * - Con ID inexistente retorna 404
     */
    public function test_get_alumno_por_id_maneja_existente_e_inexistente()
    {
        // Arrange - Creamos un alumno mediante la API
        $payload = [
            'legajo' => 54321,
            'nombre' => 'Individual Test',
            'email' => 'individual@test.com',
            'grupo_id' => 1
        ];
        $createResponse = $this->postJson($this->baseUrl, $payload, $this->headers);
        $alumnoId = $createResponse->json('id');

        // Act & Assert - ID existente
        $response = $this->getJson("{$this->baseUrl}/{$alumnoId}", $this->headers);
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $alumnoId,
                     'legajo' => 54321,
                     'nombre' => 'Individual Test'
                 ]);

        // Act & Assert - ID inexistente
        $response = $this->getJson("{$this->baseUrl}/99999", $this->headers);
        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'No query results for model [App\\Models\\Alumno] 99999'
                 ]);
    }

    /**
     * CAJA NEGRA: Verificamos validaciones de entrada
     * - Datos faltantes retornan 422
     * - Datos inválidos retornan 422
     * - Incluye mensajes de error específicos
     */
    public function test_post_alumnos_valida_datos_de_entrada()
    {
        // Test casos de validación diferentes
        $casosInvalidos = [
            // Caso 1: Datos vacíos
            [
                'payload' => [],
                'errores_esperados' => ['legajo', 'nombre', 'email', 'grupo_id']
            ],
            // Caso 2: Email inválido
            [
                'payload' => [
                    'legajo' => 11111,
                    'nombre' => 'Test',
                    'email' => 'email-invalido',
                    'grupo_id' => 1
                ],
                'errores_esperados' => ['email']
            ],
            // Caso 3: Tipos incorrectos
            [
                'payload' => [
                    'legajo' => 'no-es-numero',
                    'nombre' => 123,
                    'email' => 'valid@email.com',
                    'grupo_id' => 'no-es-numero'
                ],
                'errores_esperados' => ['legajo', 'grupo_id']
            ]
        ];

        foreach ($casosInvalidos as $caso) {
            $response = $this->postJson($this->baseUrl, $caso['payload'], $this->headers);
            
            $response->assertStatus(422)
                     ->assertJsonValidationErrors($caso['errores_esperados']);
        }
    }

    /**
     * CAJA NEGRA: Verificamos PUT /api/alumnos/{id}
     * - Actualiza correctamente
     * - Retorna datos actualizados
     * - Maneja errores de validación
     */
    public function test_put_alumnos_actualiza_correctamente()
    {
        // Arrange - Creamos alumno
        $payload = [
            'legajo' => 77777,
            'nombre' => 'Original Name',
            'email' => 'original@test.com',
            'grupo_id' => 1
        ];
        $createResponse = $this->postJson($this->baseUrl, $payload, $this->headers);
        $alumnoId = $createResponse->json('id');

        // Act - Actualizamos
        $updatePayload = [
            'legajo' => 77777,
            'nombre' => 'Updated Name',
            'email' => 'updated@test.com',
            'grupo_id' => 2
        ];
        $response = $this->putJson("{$this->baseUrl}/{$alumnoId}", $updatePayload, $this->headers);

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $alumnoId,
                     'nombre' => 'Updated Name',
                     'email' => 'updated@test.com',
                     'grupo_id' => 2
                 ]);
    }

    /**
     * CAJA NEGRA: Verificamos DELETE /api/alumnos/{id}
     * - Elimina correctamente (204 No Content)
     * - Al intentar GET después, retorna 404
     */
    public function test_delete_alumnos_elimina_correctamente()
    {
        // Arrange
        $payload = [
            'legajo' => 88888,
            'nombre' => 'To Delete',
            'email' => 'delete@test.com',
            'grupo_id' => 1
        ];
        $createResponse = $this->postJson($this->baseUrl, $payload, $this->headers);
        $alumnoId = $createResponse->json('id');

        // Act - Eliminamos
        $response = $this->deleteJson("{$this->baseUrl}/{$alumnoId}", [], $this->headers);

        // Assert - Respuesta de eliminación
        $response->assertStatus(204);

        // Assert - Verificamos que ya no existe
        $getResponse = $this->getJson("{$this->baseUrl}/{$alumnoId}", $this->headers);
        $getResponse->assertStatus(404);
    }

    /**
     * CAJA NEGRA: Verificamos autenticación
     * - Sin token retorna 401
     * - Con token inválido retorna 401
     */
    public function test_api_requiere_autenticacion()
    {
        $payload = ['legajo' => 99999, 'nombre' => 'Test', 'email' => 'test@test.com', 'grupo_id' => 1];

        // Sin Authorization header
        $response = $this->postJson($this->baseUrl, $payload, ['Accept' => 'application/json']);
        $response->assertStatus(401);

        // Con token inválido
        $headersInvalidos = array_merge($this->headers, ['Authorization' => 'Bearer token-invalido']);
        $response = $this->postJson($this->baseUrl, $payload, $headersInvalidos);
        $response->assertStatus(401);
    }

    /**
     * CAJA NEGRA: Verificamos filtros de búsqueda
     * - GET /api/alumnos?search=término
     * - Retorna solo resultados que coinciden
     */
    public function test_api_permite_filtrar_por_busqueda()
    {
        // Arrange - Creamos varios alumnos
        $alumnos = [
            ['legajo' => 1001, 'nombre' => 'Juan Pérez', 'email' => 'juan@test.com', 'grupo_id' => 1],
            ['legajo' => 1002, 'nombre' => 'María García', 'email' => 'maria@test.com', 'grupo_id' => 1],
            ['legajo' => 1003, 'nombre' => 'Carlos López', 'email' => 'carlos@test.com', 'grupo_id' => 1],
        ];

        foreach ($alumnos as $alumno) {
            $this->postJson($this->baseUrl, $alumno, $this->headers);
        }

        // Act - Buscamos por "Juan"
        $response = $this->getJson("{$this->baseUrl}?search=Juan", $this->headers);

        // Assert - Solo debe retornar Juan Pérez
        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertCount(1, $data);
        $this->assertEquals('Juan Pérez', $data[0]['nombre']);
    }

    /**
     * CAJA NEGRA: Verificamos headers de respuesta
     * - Content-Type correcto
     * - CORS headers si están configurados
     */
    public function test_api_retorna_headers_correctos()
    {
        // Act
        $response = $this->getJson($this->baseUrl, $this->headers);

        // Assert - Headers de respuesta
        $response->assertStatus(200)
                 ->assertHeader('content-type', 'application/json');
        
        // Si hay CORS configurado, podríamos verificar:
        // $response->assertHeader('access-control-allow-origin', '*');
    }
}
