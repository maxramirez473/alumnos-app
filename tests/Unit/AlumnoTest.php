<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

/**
 * Testing Unitario - Caja Blanca
 * 
 * Probamos el modelo Alumno de forma aislada
 * Conocemos la implementación interna (caja blanca)
 * - Atributos fillable
 * - Casts de tipos
 * - Relaciones
 * - Validaciones
 */
class AlumnoTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Prueba la creación básica de un alumno
     * Caja Blanca: Conocemos los atributos fillable del modelo
     */
    public function test_puede_crear_alumno_con_datos_validos()
    {
        // Arrange - Preparamos los datos
        $datosAlumno = [
            'legajo' => 12345,
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'grupo_id' => 1
        ];

        // Act - Ejecutamos la acción
        $alumno = Alumno::create($datosAlumno);

        // Assert - Verificamos el resultado
        $this->assertInstanceOf(Alumno::class, $alumno);
        $this->assertEquals(12345, $alumno->legajo);
        $this->assertEquals('Juan Pérez', $alumno->nombre);
        $this->assertEquals('juan@example.com', $alumno->email);
        $this->assertEquals(1, $alumno->grupo_id);
        
        // Verificamos que se guardó en la base de datos
        $this->assertDatabaseHas('alumnos', [
            'legajo' => 12345,
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com'
        ]);
    }

    /**
     * Prueba la relación belongsTo con Grupo
     * Caja Blanca: Conocemos que existe método grupo() que devuelve belongsTo
     */
    public function test_alumno_pertenece_a_un_grupo()
    {
        // Arrange
        $grupo = Grupo::create([
            'nombre' => 'Grupo A',
            'descripcion' => 'Descripción del grupo A'
        ]);

        $alumno = Alumno::create([
            'legajo' => 54321,
            'nombre' => 'María García',
            'email' => 'maria@example.com',
            'grupo_id' => $grupo->id
        ]);

        // Act & Assert
        $this->assertInstanceOf(Grupo::class, $alumno->grupo);
        $this->assertEquals($grupo->id, $alumno->grupo->id);
        $this->assertEquals('Grupo A', $alumno->grupo->nombre);
    }

    /**
     * Prueba los casts de atributos
     * Caja Blanca: Conocemos que legajo y grupo_id se castean a integer
     */
    public function test_atributos_son_casteados_correctamente()
    {
        // Arrange & Act - Creamos con strings que deberían castearse
        $alumno = Alumno::create([
            'legajo' => '99999',  // String que debe convertirse a integer
            'nombre' => 'Ana López',
            'email' => 'ana@example.com',
            'grupo_id' => '2'     // String que debe convertirse a integer
        ]);

        // Assert - Verificamos que los tipos son correctos
        $this->assertIsInt($alumno->legajo);
        $this->assertIsString($alumno->nombre);
        $this->assertIsString($alumno->email);
        $this->assertIsInt($alumno->grupo_id);
    }

    /**
     * Prueba validación de atributos únicos
     * Caja Blanca: Sabemos que legajo debe ser único
     */
    public function test_legajo_debe_ser_unico()
    {
        // Arrange - Creamos el primer alumno
        Alumno::create([
            'legajo' => 11111,
            'nombre' => 'Primer Alumno',
            'email' => 'primero@example.com',
            'grupo_id' => 1
        ]);

        // Act & Assert - Intentamos crear otro con el mismo legajo
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Alumno::create([
            'legajo' => 11111,  // Mismo legajo
            'nombre' => 'Segundo Alumno',
            'email' => 'segundo@example.com',
            'grupo_id' => 1
        ]);
    }

    /**
     * Prueba la relación hasMany con Notas
     * Caja Blanca: Conocemos que existe método notas() que devuelve hasMany
     */
    public function test_alumno_puede_tener_muchas_notas()
    {
        // Arrange
        $alumno = Alumno::create([
            'legajo' => 77777,
            'nombre' => 'Estudiante con Notas',
            'email' => 'estudiante@example.com',
            'grupo_id' => 1
        ]);

        // Act - Creamos notas asociadas (asumiendo que el modelo Nota existe)
        // Este test mostraría la relación, pero por ahora solo verificamos el método
        $notasRelation = $alumno->notas();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $notasRelation);
    }
}
