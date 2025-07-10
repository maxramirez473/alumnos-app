<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conceptos')->insert([
            [
                'nombre' => 'Examen Parcial',
                'descripcion' => 'Evaluación del primer semestre',
            ],
            [
                'nombre' => 'Trabajo Grupal',
                'descripcion' => 'Evaluación final del curso',
            ],
            [
                'nombre' => 'Concepto Extra',
                'descripcion' => 'Proyecto práctico a entregar al final del curso',
            ],
        ]);
    }
}
