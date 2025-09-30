<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para el modelo Grupo
 * 
 * Genera grupos de estudio realistas para testing
 */
class GrupoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materias = [
            'Matemáticas', 'Historia', 'Ciencias', 'Literatura', 'Física', 
            'Química', 'Biología', 'Geografía', 'Arte', 'Música',
            'Inglés', 'Filosofía', 'Educación Física', 'Informática'
        ];

        $niveles = ['Básico', 'Intermedio', 'Avanzado'];
        $turnos = ['Mañana', 'Tarde', 'Noche'];

        $materia = $this->faker->randomElement($materias);
        $nivel = $this->faker->randomElement($niveles);
        $turno = $this->faker->randomElement($turnos);

        return [
            'nombre' => "{$materia} {$nivel} - {$turno}",
            'descripcion' => "Grupo de {$materia} nivel {$nivel} para estudiantes del turno {$turno}. " .
                           "Enfoque en " . $this->faker->sentence(4),
        ];
    }

    /**
     * Estado para grupos de testing específico
     */
    public function paraTestingUnitario(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Grupo Test Unitario',
            'descripcion' => 'Grupo creado específicamente para pruebas unitarias del sistema.',
        ]);
    }

    /**
     * Estado para grupos de integración
     */
    public function paraTestingIntegracion(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Grupo Integración ' . $this->faker->randomLetter(),
            'descripcion' => 'Grupo para testing de integración entre componentes.',
        ]);
    }

    /**
     * Grupos con nombres más realistas para E2E
     */
    public function grupoRealista(): static
    {
        $cursos = [
            ['nombre' => '1° Año A', 'descripcion' => 'Primer año, división A - Turno mañana'],
            ['nombre' => '2° Año B', 'descripcion' => 'Segundo año, división B - Turno tarde'],
            ['nombre' => '3° Año C', 'descripcion' => 'Tercer año, división C - Turno mañana'],
            ['nombre' => 'Matemáticas Avanzadas', 'descripcion' => 'Curso especializado en matemáticas para estudiantes destacados'],
            ['nombre' => 'Ciencias Naturales', 'descripcion' => 'Laboratorio de ciencias con enfoque experimental'],
        ];

        $curso = $this->faker->randomElement($cursos);
        
        return $this->state(fn (array $attributes) => $curso);
    }
}
