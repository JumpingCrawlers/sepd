<?php

namespace App;

use App\MenuItem;
use App\Partesgrafica;

class MenuItemPagina extends Model
{
    protected $table = 'menu_item_pagina';
    protected $with = ['menuitem', 'partesgrafica'];
    
    /***************************************
     * WITH ..... relaciones de página
     *
     **************************************/

    /**
     * Datos del menú item asociado: @return MenuItem
     */
    public function menuitem() {
        return $this->belongsTo('App\MenuItem', 'menu_item_id');
    }
    
    /**
     * Datos del icono asociado: @return PartesGrafica
     */
    public function partesgrafica() {
        return $this->belongsTo('App\Partesgrafica', 'partesgrafica_id');
    }
    
}
