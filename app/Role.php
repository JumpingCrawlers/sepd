<?php

namespace App;

class Role extends Model
{

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * El rol institucional del sitio
     * 
     * @return Role
     */
    public static function rolInstitucional() {
        
        return static::where('name', 'institucional')->first();

    }
    
    /**
     * El rol Admin del sitio
     * 
     * @return Role
     */
    public static function rolAdmin() {
        
        return static::where('name', 'admin')->first();

    }
    
      /**
     * Nombre del rol
     * 
     * @param id integer 
     * @returns string nombre del rol
     */
    public static function getNombreRol($id) {
        
        return static::find($id)->name;

    }
    
}
