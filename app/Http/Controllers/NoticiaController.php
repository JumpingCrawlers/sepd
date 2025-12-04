<?php

namespace App\Http\Controllers;

use App\Pagina;
use App\Noticia;
use App\MenuItem;

use Illuminate\Http\Request;
// Las noticias son solo para socios
use Auth;

class NoticiaController extends Controller
{

    protected $miga_pan = '> Noticias';
    
    public function index($seccion = null) {
        
        /* recuperar la colección de noticias */
        switch($seccion) {
            case 'seccion_gastro':
                $coleccion = Noticia::porSeccion('Noticias GASTRO');
                break;
            case 'canal_sepd':
                $coleccion = Noticia::porSeccion('Canal SEPD');
                break;
            case 'gastroMIR':
                $coleccion = Noticia::porSeccion('GastroMIR');
                break;
            default:
                $coleccion = Noticia::paginate(setting('site.elementos_pagina')); // por defecto todos
                break;
        }

        /* recuperar la colección de noticias */
        $coleccion = Noticia::paginate(setting('site.elementos_pagina'));
        
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('noticias');
        
        return view('noticias.index')->with([
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
     * @return collection Noticia
     */
    public function listaNoticia(Request $request) {
        
        return Noticia::filtrados($request);
        
    }

    public function show(Noticia $noticia) {
        
        // La vista de una noticias está protegida (solo conectados)
        if (!Auth::user()) {
            
            $vista = 'auth.login';

        } else {
            
            $vista = 'noticias.show';

        }

        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('noticias-detalle');

        return view($vista)->with([
            'noticia' => $noticia,
            'pagina' => $pagina,
            'miga_pan' => $this->miga_pan
        ]);

    }
    
}
