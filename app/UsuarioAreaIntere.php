<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioAreaIntere extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'usuarios_areas_interes';

    /**
     * Campos asignables
     * 
     * @var array
     */
    protected $fillable = [
        'usuario_id',
        'area_id'
    ];

    /**
     * Obtener area
     * 
     * @return Area
     */
    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
