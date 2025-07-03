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
}
