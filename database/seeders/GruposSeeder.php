<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grupos')->insert([
            ['numero'=>1,'nombre' => 'Vocación +'],
            ['numero'=>2,'nombre' => 'LotManager'],
            ['numero'=>3,'nombre' => 'BibliOS'],
            ['numero'=>4,'nombre' => 'Manuel Belgrano 2.0'],
            ['numero'=>5,'nombre' => 'Let Me Cook'],
            ['numero'=>6,'nombre' => 'TurnosYA'],
            ['numero'=>7,'nombre' => 'Hospedaje'],
            ['numero'=>8,'nombre' => 'Bagmmerce'],
            ['numero'=>9,'nombre' => 'DSMB'],
            ['numero'=>10,'nombre' => 'Memoriae'],
            ['numero'=>11,'nombre' => 'SIRMU'],
        ]);
    }
}
