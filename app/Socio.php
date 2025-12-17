<?php

namespace App;

// Trait para guardar los datos de provincia
use App\Traits\Provincia;

class Socio extends Model
{
    use Provincia;

    public $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $dates = [ 'fecha_reg', 'fecha_cargo' ];
    

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/
    
    // Proceso de validar registro de usuario
    // Comprobar si ya existe afiliacion por dni o email (antiguo form_validar.php)
    public static function checkAfiliacionRegistro($dni, $email) {

        return static::join('socios__afiliacion', 'socios.old_id_usuario', '=', 'socios__afiliacion.id_usuario')
                            ->where('socios__afiliacion.borrado', '=', 0)
                            ->where(function ($query) use ($dni, $email) {
                                $query->where('socios.dni', 'like', $dni.'%')
                                      ->orWhere('socios.email', '=', $email);
                            })
                            ->count() > 0;

    }

}
