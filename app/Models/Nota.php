<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $fillable = [
        'nota',
        'alumno_id',
        'concepto_id',
    ];

    protected $table = 'notas';

    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
