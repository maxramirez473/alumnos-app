<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrega;

class EntregasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entrega::create([
            'nombre' => 'Propuesta',
            'descripcion' => 'Entrega correspondiente a la propuesta de trabajo integrador, donde se evalua a viabilidad del mismo, objetivos, metodologias de gestión de grupo y alcance'
        ]);
    }
}
