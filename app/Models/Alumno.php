<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // Define the fillable attributes for mass assignment
    protected $fillable = ['legajo', 'nombre', 'email', 'grupo_id'];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    // Define the table name
    protected $table = 'alumnos';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'legajo' => 'integer',
        'nombre' => 'string',
        'email' => 'string',
        'grupo_id' => 'integer',
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
     * The attributes that should be guarded from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to Carbon instances.
     *
     * @var array
     */
    protected $dates = [];


    /**
     * Relaciones del modelo Alumno.
     */
    public function grupo()
    {
        return $this->belongsTo('App\Models\Grupo', 'grupo_id');
    }

    public function nota()
    {
        return $this->hasMany('App\Models\Nota', 'alumno_id');
    }
}
