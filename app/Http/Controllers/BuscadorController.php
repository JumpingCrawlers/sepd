<?php

namespace App\Http\Controllers;

use App\Pagina;
use App\Buscador;

class BuscadorController extends Controller
{

    public function index() {
       
        
        $pagina = Pagina::getPaginaBySlug('buscador');
        //$nombre_menu = 'formacion';

        return view('buscador.resultados', compact('pagina'));

    }
    
}
