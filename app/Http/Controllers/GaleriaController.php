<?php

namespace App\Http\Controllers;

use App\Galeria;
use App\Pagina;
use App\MenuItem;

use Illuminate\Http\Request;
// para redireccionar a la página de galeria cuando no se encuenta una carpeta
use Illuminate\Support\Facades\URL;

class GaleriaController extends Controller
{

    public function index($carpeta1 = null) {
        
        if ($carpeta1 !== null) {
            $existe = Galeria::existeCarpeta($carpeta1);
            if ($existe == 0) {
                session()->flash('alerta_flash', getHtmlIconoFlash('ko').' No se ha encontrado esta carpeta de imágenes');
                return redirect(URL::route('galeria'));
            }
        }

        /* recuperar la colección de fotos de la galería */
        $coleccion = Galeria::paginate(setting('site.elementos_pagina'));
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('galeria');
        
        return view('galeria.index', compact('coleccion', 'pagina'));

    }


    /**
     * Recuperar los registros de galeria que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Galeria
     */
    public function listaGaleria(Request $request) {
        
        return Galeria::filtrados($request);
        
    }

    /**
     * Recuperar el nombre de la carpeta pasada por parámetro en la url
     * 
     * API
     * 
     * @param Request $request
     * @return string nombre
     */
    public function getNombreCarpeta(Request $request) {
        
        return Galeria::getNombreCarpeta($request);
        
    }

    
}
