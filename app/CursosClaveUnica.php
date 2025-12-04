<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursosClaveUnica extends Model
{
    protected $fillable = [
        'curso_id',
        'password',
        'limit_codes',
    ];

    public function cursos()
    {
        return $this->hasOne(Curso::class, 'id', 'curso_id');
    }
}
