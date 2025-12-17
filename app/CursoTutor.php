<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\UsuarioTutor;


class CursoTutor extends Model
{
    protected $table = 'cursos_tutores';
    /**
     * Obtener el tutor de la relacion cursos_tutores
     *
     */
    public function usuario_tutor()
    {
        return $this->belongsTo(UsuarioTutor::class, 'usuario_tutor_id');
    }
    /**
     * Obtener el curso de la relacion cursos_tutores
     *
     */
    public function curso()
     {
         return $this->belongsTo(Curso::class);
    }

    /**
     * 3 Ways - Alex
     * Obtener mensajes del usuario siendo emisor
     */
    public function mensajes()
    {
        $usuario_tutor = UsuarioTutor::where('id', '=', $this->usuario_tutor_id)->first();
        $usuario_id = $usuario_tutor->usuario_id;
        return $this->hasMany(Mensaje::class, "tema", "curso_id")->where(function ($query) use ($usuario_id) {
                                                                            $query->where('emisor', '=', $usuario_id)
                                                                                  ->orWhere('receptor', '=', $usuario_id)
                                                                                  ;
                                                                        })->orderByDesc('created_at');;
    }

    /**
     * 3 Ways - Alex
     * Obtener mensajes del usuario siendo emisor
     */
    public function mensajesPorUsuario($id)
    {      
        $usuario_tutor = UsuarioTutor::where('id', '=', $this->usuario_tutor_id)->first();
        $usuario_id = $usuario_tutor->usuario_id;
        return $this->hasMany(Mensaje::class, "tema", "curso_id")->where(function ($query) use ($usuario_id,$id) {
                                                                            $query->where([['emisor', '=', $usuario_id],['receptor', '=', $id]])
                                                                                  ->orWhere([['receptor', '=', $usuario_id],['emisor', '=', $id]])
                                                                                  ;
                                                                        })->orderBy('created_at');
    }

}