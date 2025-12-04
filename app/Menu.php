<?php

namespace App;

class Menu extends Model
{
    protected $with = ['role'];
    
    /***************************************
     * WITH ..... relaciones de página
     *
     **************************************/

    /**
     * Datos del rol asociado: @return Role
     */
    public function role() {
        return $this->belongsTo('App\Role');
    }

    /**
     * Menu correspondiente al rol
     * 
     * @param rol // integer, role_id
     * @return Menu
     */
    public static function getMenuByRolId($rol) {
        
        return static::where('role_id', $rol)->first();

    }
    
    /**
     * El menu institucional
     * 
     * @return Menu
     */
    public static function getMenuInstitucional() {
        
        return static::where('name', 'institucional')->first();

    }

}
