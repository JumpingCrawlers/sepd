<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioSocio extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_socios';

    /**
     * Obtener tipo de socio
     * 
     * @return SocioTipo
     */
    public function tipo()
    {
        return $this->belongsTo(SocioTipo::class, 'tipo_socio');
    }

    public function reconocimientos()
    {
        return $this->hasMany(SocioReconocimiento::class, 'socio_id');
    }
}
