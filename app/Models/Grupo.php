<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['nombre', 'numero'];
    protected $table = 'grupos';
    protected $primaryKey = 'id';

    public $timestamps = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre' => 'string',
        'numero' => 'integer',
    ];          

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = []; 


    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [];


    /**
     * Get the alumnos for the grupo.
     */
    public function alumnos()
    {
        return $this->hasMany('App\Models\Alumno', 'grupo_id'); 
    }

    /**
     * Relación muchos a muchos con entregas a través de la tabla pivote
     */
    public function entregas()
    {
        return $this->belongsToMany(Entrega::class, 'entrega_grupo')
                    ->withPivot(['fecha_entrega', 'calificacion', 'comentarios', 'fecha_calificacion'])
                    ->withTimestamps();
    }

    /**
     * Obtener entregas completadas (que tienen fecha_entrega)
     */
    public function entregasCompletadas()
    {
        return $this->entregas()->whereNotNull('entrega_grupo.fecha_entrega');
    }

    /**
     * Obtener entregas pendientes (que no tienen fecha_entrega)
     */
    public function entregasPendientes()
    {
        return $this->entregas()->whereNull('entrega_grupo.fecha_entrega');
    }

    /**
     * Obtener entregas calificadas
     */
    public function entregasCalificadas()
    {
        return $this->entregas()->whereNotNull('entrega_grupo.calificacion');
    }

    /**
     * Obtener entregas sin calificar
     */
    public function entregasSinCalificar()
    {
        return $this->entregas()->whereNull('entrega_grupo.calificacion');
    }
}
