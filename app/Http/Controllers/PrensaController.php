<?php

namespace App\Http\Controllers;

use App\Pagina;
use App\Prensa;
use App\MenuItem;
use Illuminate\Http\Request;

class PrensaController extends Controller
{

    public function index() {
        
        /* recuperar la colección de notas de prensa */
        $coleccion = Prensa::paginate(setting('site.elementos_pagina'));
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('prensa');
        
        return view('prensa.index', compact('coleccion', 'pagina'));

    }

    /**
     * Recuperar los registros de prensa que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Prensa
     */
    public function listaPrensa(Request $request) {
        
        return Prensa::filtrados($request);
        
    }

    public function show(Prensa $nota) {
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('prensa');

        return view('prensa.show', compact('nota', 'pagina'));

    }
    
}
