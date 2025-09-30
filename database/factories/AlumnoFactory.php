<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Grupo;

/**
 * Factory para el modelo Alumno
 * 
 * Genera datos de prueba realistas y consistentes
 * Usado por todos los tipos de testing
 */
class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legajo' => $this->faker->unique()->numberBetween(10000, 99999),
            'nombre' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'grupo_id' => Grupo::factory(), // Crea un grupo automáticamente
        ];
    }

    /**
     * Estado para alumnos con datos específicos para testing
     */
    public function paraTestingUnitario(): static
    {
        return $this->state(fn (array $attributes) => [
            'legajo' => 99999,
            'nombre' => 'Alumno de Prueba Unitaria',
            'email' => 'unitario@test.com',
        ]);
    }

    /**
     * Estado para alumnos de integración
     */
    public function paraTestingIntegracion(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Alumno Integración ' . $this->faker->firstName(),
            'email' => 'integracion.' . $this->faker->unique()->word() . '@test.com',
        ]);
    }

    /**
     * Estado para testing de API
     */
    public function paraTestingApi(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'API Test Student',
            'email' => 'api.test@example.com',
            'legajo' => $this->faker->unique()->numberBetween(80000, 89999),
        ]);
    }

    /**
     * Estado para testing E2E
     */
    public function paraTestingE2E(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'E2E ' . $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'email' => 'e2e.' . $this->faker->unique()->word() . '@behavioral.test',
        ]);
    }

    /**
     * Genera estudiantes con nombres hispanos para realismo
     */
    public function estudianteHispano(): static
    {
        $nombresHispanos = [
            'José', 'María', 'Antonio', 'Carmen', 'Manuel', 'Pilar',
            'Francisco', 'Dolores', 'David', 'Josefa', 'Daniel', 'Ana'
        ];
        
        $apellidosHispanos = [
            'García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez',
            'Sánchez', 'Pérez', 'Gómez', 'Martín', 'Jiménez', 'Ruiz'
        ];

        return $this->state(fn (array $attributes) => [
            'nombre' => $this->faker->randomElement($nombresHispanos) . ' ' . 
                    $this->faker->randomElement($apellidosHispanos),
            'email' => strtolower(str_replace(' ', '.', $attributes['nombre'] ?? 'estudiante')) . 
                    $this->faker->numberBetween(1, 999) . '@colegio.edu',
        ]);
    }

    /**
     * Genera estudiantes con legajos en rangos específicos
     */
    public function conLegajoEnRango(int $inicio, int $fin): static
    {
        return $this->state(fn (array $attributes) => [
            'legajo' => $this->faker->unique()->numberBetween($inicio, $fin),
        ]);
    }
}
