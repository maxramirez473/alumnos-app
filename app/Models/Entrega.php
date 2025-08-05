<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    protected $table = 'entregas';
    
    /**
     * Relación muchos a muchos con grupos a través de la tabla pivote
     */
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'entrega_grupo')
                    ->withPivot(['fecha', 'estado', 'observaciones'])
                    ->withTimestamps();
    }

    /**
     * Obtener grupos por estado de entrega
     */
    public function gruposPorEstado($estado)
    {
        return $this->grupos()->wherePivot('estado', $estado);
    }

    /**
     * Obtener grupos con entrega pendiente
     */
    public function gruposPendientes()
    {
        return $this->gruposPorEstado('pendiente');
    }

    /**
     * Obtener grupos con entrega completada
     */
    public function gruposCompletados()
    {
        return $this->gruposPorEstado('completada');
    }

    /**
     * Obtener grupos con entrega en progreso
     */
    public function gruposEnProgreso()
    {
        return $this->gruposPorEstado('en_progreso');
    }

    /**
     * Obtener grupos con entrega retrasada
     */
    public function gruposRetrasados()
    {
        return $this->gruposPorEstado('retrasada');
    }

    /**
     * Asignar entrega a un grupo con datos adicionales
     */
    public function asignarAGrupo($grupoId, $fecha = null, $estado = 'pendiente', $observaciones = null)
    {
        return $this->grupos()->attach($grupoId, [
            'fecha' => $fecha,
            'estado' => $estado,
            'observaciones' => $observaciones,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Actualizar estado de entrega para un grupo específico
     */
    public function actualizarEstadoGrupo($grupoId, $estado, $observaciones = null)
    {
        return $this->grupos()->updateExistingPivot($grupoId, [
            'estado' => $estado,
            'observaciones' => $observaciones,
            'updated_at' => now()
        ]);
    }
    public $timestamps = false;
    protected $primaryKey = 'id';
}
