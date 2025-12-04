<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProyectoFormativo extends Model
{
    protected $table = 'proyecto_formativos';

    protected $fillable = ['nombre', 'descripcion','descripcion_resumen'];
    
    

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

}
