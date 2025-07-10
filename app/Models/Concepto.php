<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $table = 'conceptos';

    protected $primaryKey = 'id';
    public $timestamps = false;

    public function notas()
    {
        return $this->hasMany(Nota::class, 'concepto_id');
    }
}
