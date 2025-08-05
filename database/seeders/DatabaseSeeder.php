<?php

namespace Database\Seeders;

use App\Models\Entrega;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GruposSeeder::class,
            ConceptosSeeder::class,
            EntregasSeeder::class
            // Add other seeders here if needed
        ]);
    }
}
