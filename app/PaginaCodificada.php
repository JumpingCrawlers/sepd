<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaginaCodificada extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'paginas_codificadas';
    protected $primaryKey = 'codigo';

    /**
     * Obtener página relacionada
     *
     * @return App\Pagina
     */
    public function pagina()
    {
        return $this->belongsTo(Pagina::class, 'pagina_id');
    }
}
