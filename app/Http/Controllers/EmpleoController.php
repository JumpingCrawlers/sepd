<?php

namespace App\Http\Controllers;

use App\Pagina;
use App\Empleo;
use Illuminate\Http\Request;
// restringir acceso a usuarios
use Auth;

class EmpleoController extends Controller
{
    
    protected $miga_pan = '> Ofertas de empleo';

    public function index() {
        
        /* recuperar la colección de notas de prensa */
        $coleccion = Empleo::paginate(setting('site.elementos_pagina'));
        
        /* página contenedora info-sepd o hepatology */
        $pagina = Pagina::getPaginaBySlug('empleos');
        
        return view('empleo.index')->with([
            'coleccion' => $coleccion,
            'pagina' => $pagina,
            'miga_pan' => $this->miga_pan
        ]);

    }

    /**
     * Recuperar los registros de prensa que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Prensa
     */
    public function listaEmpleo(Request $request) {
        
        return Empleo::filtrados($request);

    }
    
    /**
     * Detalle de un empleo
     */
    public function show(Empleo $empleo) {
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('empleos');
        
        // Los empleos es restringido a usuarios
        // controlar si el contenido es retringido
        if (!Auth::user()) {
            
            return view('auth.login', compact('pagina'));

        }

        return view('empleo.show', compact('empleo', 'pagina'));

    }

}
