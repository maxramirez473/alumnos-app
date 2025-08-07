<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrega;
use Carbon\Carbon;

class EntregasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entrega::create([
            'titulo' => 'Propuesta',
            'descripcion' => 'Entrega correspondiente a la propuesta de trabajo integrador, donde se evalúa la viabilidad del mismo, objetivos, metodologías de gestión de grupo y alcance',
            'fecha_limite' => Carbon::now()->addDays(15)->setTime(23, 59, 0),
        ]);
        
        Entrega::create([
            'titulo' => 'Primer entrega',
            'descripcion' => 'Primer informe de avance del proyecto, mostrando el diseño frontend inicial y los objetivos',
            'fecha_limite' => Carbon::now()->addDays(30)->setTime(23, 59, 0),
        ]);
        
        Entrega::create([
            'titulo' => 'Segunda entrega',
            'descripcion' => 'Segundo informe de avance del proyecto, donde se muestra el progreso realizado en las últimas semanas, incluyendo la implementación de base de datos y funcionalidades principales',
            'fecha_limite' => Carbon::now()->addDays(60)->setTime(23, 59, 0),
        ]);
        Entrega::create([
            'titulo' => 'Entregable Final',
            'descripcion' => 'Entrega final que incluye el proyecto completo, documentación técnica y manual de usuario',
            'fecha_limite' => Carbon::now()->addDays(60)->setTime(23, 59, 0),
        ]);
    }
}
