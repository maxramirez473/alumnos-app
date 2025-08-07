<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Entrega extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_limite',
    ];

    protected $table = 'entregas';

    protected $casts = [
        'fecha_limite' => 'datetime',
    ];

    /**
     * Relación muchos a muchos con grupos a través de la tabla pivote
     */
    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'entrega_grupo')
                    ->withPivot(['fecha_entrega', 'calificacion', 'comentarios', 'fecha_calificacion'])
                    ->withTimestamps();
    }

    /**
     * Obtener grupos con entrega completada (que tienen fecha_entrega)
     */
    public function gruposEntregados()
    {
        return $this->grupos()->whereNotNull('entrega_grupo.fecha_entrega');
    }

    /**
     * Obtener grupos con entrega pendiente (que no tienen fecha_entrega)
     */
    public function gruposPendientes()
    {
        return $this->grupos()->whereNull('entrega_grupo.fecha_entrega');
    }

    /**
     * Obtener grupos calificados
     */
    public function gruposCalificados()
    {
        return $this->grupos()->whereNotNull('entrega_grupo.calificacion');
    }

    /**
     * Obtener grupos sin calificar
     */
    public function gruposSinCalificar()
    {
        return $this->grupos()->whereNull('entrega_grupo.calificacion');
    }

    /**
     * Verificar si la entrega está vencida
     */
    public function getEsVencidaAttribute()
    {
        return $this->fecha_limite && $this->fecha_limite < now();
    }

    /**
     * Calcular el porcentaje de progreso basado en entregas recibidas
     */
    public function getPorcentajeProgresoAttribute()
    {
        $total = $this->grupos->count();
        if ($total === 0) return 0;
        
        $entregadas = $this->gruposEntregados()->count();
        return round(($entregadas / $total) * 100, 2);
    }
}
