<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Usuario;

class UsuarioTutor extends Model
{
    protected $table = 'usuarios_tutores';
    /**
     * Obtener los cursos del tutor
     *
     */
    public function cursos()
    {
        return $this->hasMany(CursoTutor::class);
    }
    /**
     * Obtener los datos del usuario tutor
     *
     */
    public function usuario()
    {
         return $this->belongsTo(User::class);
    }

}