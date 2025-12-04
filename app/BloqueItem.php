<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CursoBloque;
use App\UsuarioIem;

class BloqueItem extends Model
{
    protected $table = 'cursos_bloques_items';
    /**
      * Obtener el bloque que es dueño del item
    */
    public function bloque()
    {
        return $this->belongsTo(BloqueItem::class);
    }

    /* 3 Ways - Alexis */

    // Comprobar si el usuario ya ha accedido al item
    public function usuario_item($userId)
    {
        return $this->hasOne(UsuarioItem::class, 'item_id')->where('usuario_id', '=', $userId)->first();
    } 
}
