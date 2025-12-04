<?php

namespace App\Http\Controllers;

use App\BloqueItem;

class DiapositivasController extends Controller {

    /**
     * Mostrar diapositiva dependiendo del id del bloque
     * 
     * @param Integer $id
     * 
     * @return void
     */
    public function index($id) {
        $item = BloqueItem::find($id);
        if (!$item) $migrado = true;
        else $migrado = ($item->contenido != "diapositivas");
        
        return view('bloques.diapositivas', [
            'migrado' => $migrado,
            'enlace' => $id
        ]);
    }

}