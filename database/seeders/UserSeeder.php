<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador de prueba
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@alumnos.app',
            'rol' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Crear usuario regular de prueba
        User::create([
            'name' => 'Usuario de Prueba',
            'email' => 'usuario@alumnos.app',
            'rol' => 'user',
            'password' => Hash::make('password123'),
        ]);
    }
}
