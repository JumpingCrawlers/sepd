<?php

namespace App;

class MenuItem extends Model
{
    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Devuelve la Miga de Pan para el menu_item pasado por parámetro
     * @param integer (menu_item.id) $id
     * @returns string
     */
    public static function getMigaPan($id) {
        
        $item = static::find($id);
        
        if ($item) {
            if ($item->parent_id){
                return static::getMigaPan($item->parent_id)." - ".$item->title;
            }
            return '> '.$item->title;
        }
        
        return '';

    }

    /**
     * Devuelve la lista de IDS del menú para marcarlos como activos
     * @param integer (menu_item.id) $id
     * @returns string
     */
    public static function getListaIdsActivos($id) {
        
        $item = static::find($id);
        
        if ($item) {
            if ($item->parent_id){
                return static::getListaIdsActivos($item->parent_id)."-".$item->id."-";
            }
            return '-'.$item->id."-";
        }
        
        return '-';
        
    }

    /**
     * Devuelve la primera opción de un menú (usado en preview)
     * @param integer (menu.id) $id
     * @returns MenuItem
     */
    public static function getPrimerItemMenu($id) {
        
        return static::where('menu_id', $id)->first();

    }

}
