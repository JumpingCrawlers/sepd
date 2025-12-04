<?php

namespace App\Http\Controllers;

use App\Pagina;
use App\Dossier;
use App\MenuItem;
use Illuminate\Http\Request;

class RepercusionController extends Controller
{

    public function index() {
        
        /* recuperar la colección de dossiers */
        $coleccion = Dossier::whereNull('deleted_at')->paginate(setting('site.elementos_pagina'));
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('presencia_medios');

        return view('repercusion.index', compact('coleccion', 'pagina'));

    }

    /**
     * Recuperar los registros de dossier que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Dossier
     */
    public function listaDossier(Request $request) {
        
        return Dossier::filtrados($request);
        
    }
    
}
