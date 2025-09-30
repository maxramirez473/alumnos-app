<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Grupo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

class GrupoTest extends BaseTestCase
{

    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_add_group()
    {
        $datosGrupo = [
            'numero' => 500,
            'nombre' => 'Grupo Test'
        ];

        $this->assertIsArray($datosGrupo); // Verifica que $datosGrupo  es un array
        $this->assertArrayHasKey('numero', $datosGrupo); // Verifica que el array tiene la clave 'numero'
        $this->assertArrayHasKey('nombre', $datosGrupo); // Verifica que el array tiene la clave 'nombre'
        $this->assertIsInt($datosGrupo['numero']); // Verifica que 'numero' es un entero
        $this->assertIsString($datosGrupo['nombre']); // Verifica que 'nombre' es una cadena

        $grupo = Grupo::create($datosGrupo); //Creación del grupo

        $this->assertInstanceOf(Grupo::class, $grupo);
        $this->assertEquals(500, $grupo->numero);
        $this->assertEquals('Grupo Test', $grupo->nombre);
    }

    public function test_modify_group()
    {
        // Crear un grupo inicial
        $grupo = Grupo::create([
            'numero' => 600,
            'nombre' => 'Grupo Original'
        ]);

        $this->assertInstanceOf(Grupo::class, $grupo);
        $this->assertEquals(600, $grupo->numero);
        $this->assertEquals('Grupo Original', $grupo->nombre);

        // Modificar el grupo
        $grupo->numero = 700;
        $grupo->nombre = 'Grupo Modificado';
        $grupo->save();

        // Refrescar la instancia desde la base de datos
        $grupo->refresh();

        // Verificar los cambios
        $this->assertEquals(700, $grupo->numero);
        $this->assertEquals('Grupo Modificado', $grupo->nombre);
    }
}
