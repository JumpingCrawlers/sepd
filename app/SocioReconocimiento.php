<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocioReconocimiento extends Model
{
    /* 3 Ways - Alexis Bogado  */

    use SoftDeletes;

    protected $table = 'socios_reconocimientos';

    /**
     * Obtener socio
     *
     * @return App\UsuarioSocio
     */
    public function socio()
    {
        return $this->belongsTo(UsuarioSocio::class, 'socio_id');
    }

    /**
     * Obtener el tipo de reconocimiento
     *
     * @return App\SocioTipoReconocimiento
     */
    public function tipo_reconocimiento()
    {
        return $this->belongsTo(SocioTipoReconocimiento::class, 'tipo_reconocimiento_id');
    }
}