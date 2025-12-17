<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pagina;

class AulaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Mostrar panel Aula Virtual
     *
     * @return void
     */
    public function index() {
        $pagina = Pagina::getPaginaBySlug('aula');
        
        return view('formacion.aula', compact('pagina'));
    }
}
