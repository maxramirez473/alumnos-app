<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['nombre', 'numero'];
    protected $table = 'grupos';
    protected $primaryKey = 'id';

    public $timestamps = false;

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
                    ->withPivot(['fecha', 'estado', 'observaciones'])
                    ->withTimestamps();
    }

    /**
     * Obtener entregas por estado
     */
    public function entregasPorEstado($estado)
    {
        return $this->entregas()->wherePivot('estado', $estado);
    }

    /**
     * Obtener entregas pendientes
     */
    public function entregasPendientes()
    {
        return $this->entregasPorEstado('pendiente');
    }

    /**
     * Obtener entregas completadas
     */
    public function entregasCompletadas()
    {
        return $this->entregasPorEstado('completada');
    }

    /**
     * Obtener entregas en progreso
     */
    public function entregasEnProgreso()
    {
        return $this->entregasPorEstado('en_progreso');
    }

    /**
     * Obtener entregas retrasadas
     */
    public function entregasRetrasadas()
    {
        return $this->entregasPorEstado('retrasada');
    }
}
