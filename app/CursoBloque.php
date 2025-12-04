<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BloqueItem;
use App\Cuestionario;
use Illuminate\Database\Eloquent\SoftDeletes;


class CursoBloque extends Model
{
    use SoftDeletes;
    protected $table = 'cursos_bloques';
    
    // /**
    //  * Obtener el curso que es dueño del bloque
    //  */
     public function curso()
     {
         return $this->belongsTo(Curso::class);
    }
    
    /**
    * Obtener items del bloque
    */
    public function items()
    {
        return $this->hasMany(BloqueItem::class, 'bloque_id')->where('deleted_at', null)->orderBy('orden');
    }
    /**
    * Obtener cuestionario del bloque
    */
    public function cuestionarios()
    {
        return $this->hasMany(Cuestionario::class, 'bloque_id')->where('deleted_at', null);
    }
}
