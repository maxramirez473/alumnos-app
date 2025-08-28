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
        User::firstOrCreate(
        ['email' => 'admin@alumnos.app'],
        [
            'name' => 'Administrador',
            'rol' => 'admin',
            'password' => Hash::make('password123'),
        ]
);

        // Crear usuario regular de prueba
        User::firstOrCreate(
        ['email' => 'usuario@alumnos.app'],
        [
            'name' => 'Usuario de Prueba',
            'rol' => 'user',
            'password' => Hash::make('password123'),
        ]
        );
    }
}
