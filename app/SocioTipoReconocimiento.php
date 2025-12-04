<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocioTipoReconocimiento extends Model
{
    /* 3 Ways - Alexis Bogado */

    use SoftDeletes;

    protected $table = 'socios_tipos_reconocimientos';

    /**
     * Obtener el CertificadoSocio
     * 
     * @return CertificadosSocio|null
     */
    public function certificados_socio ()
    {
        return $this->hasOne(CertificadosSocio::class, 'reconocimiento_id');
    }
}
