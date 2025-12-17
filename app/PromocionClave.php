<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromocionClave extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'promociones_claves';

    /**
     * Obtener promoción relacionada a la clave
     *
     * @return \App\Promocion
     */
    public function promocion()
    {
        return $this->belongsTo(Promocion::class, 'promocion_id');
    }
}
