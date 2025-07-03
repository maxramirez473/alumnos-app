<?php

namespace App\Imports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlumnosImport implements ToModel, WithHeadingRow, WithValidation, WithMapping
{
    public function map($row): array
    {
        // Normalizar las claves del array a minúsculas
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            $normalizedRow[$normalizedKey] = $value;
        }
        
        return [
            'legajo' => $normalizedRow['legajo'] ?? null,
            'nombres' => $normalizedRow['nombres'] ?? null,
            'email' => $normalizedRow['email'] ?? null,
        ];
    }

    public function model(array $row)
    {
        return new Alumno([
            'legajo' => $row['legajo'],
            'nombre' => $row['nombres'],
            'email' => $row['email'],
            'grupo_id' => null
        ]);
    }

    public function rules(): array
    {
        return [
            'legajo' => 'required|integer|unique:alumnos,legajo',
            'nombres' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:alumnos,email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'legajo.required' => 'El legajo es obligatorio.',
            'legajo.unique' => 'El legajo ya existe.',
            'nombres.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'El email ya existe.',
        ];
    }
}