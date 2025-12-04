<?php

namespace App\Http\Controllers;

use App\Pagina;
use App\Revista;
use App\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RevistaController extends Controller
{

    protected $usuario;

    /**
     * Constructor
     * Aplica middleware y recupera el usuario (web o API)
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->initUsuario($request);
            return $next($request);
        });
    }


    /**
     * Inicializa la variable $usuario
     * Funciona para web y API
     *
     * @param Request|null $request
     */
    protected function initUsuario($request = null)
    {
        $request = $request ?: request();

        // Primero intenta obtener usuario vía API (guard api)
        $usuarioApi = $request->usuario_logueado;

        // Luego intenta usuario web
        $usuarioWeb = auth()->guard('web')->user();

        // Asigna a la propiedad
        $this->usuario = $usuarioApi ?: $usuarioWeb;
    }
    
    public function index()
    {
        if (!Auth::user()) {
            return redirect('/login');
        } else if (!Auth::user() || !Auth::user()->es_socio()) {
            return redirect('/hazte_socio');
        }
        /* recuperar la colección de revistas */
        $coleccion = Revista::paginate(setting('site.elementos_pagina'));

        /* página contenedora info-sepd o hepatology */
        if (strpos(url()->current(), 'info-sepd') !== false) {
            $pagina = Pagina::getPaginaBySlug('info-sepd');
        } else if (strpos(url()->current(), 'gi-hepatology-news') !== false) {
            $pagina = Pagina::getPaginaBySlug('gi-hepatology-news');
        } else if (strpos(url()->current(), 'international-gastroenterology-news') !== false) {
            /**
             * 3ways Euro Fuenmayor
             * Agregado nuevo condicional para cargar la pagina international-gastroenterology-news
             */
            $pagina = Pagina::getPaginaBySlug('international-gastroenterology-news');
        } else {
            $pagina = Pagina::getPaginaBySlug('gastro-news');
        }

        return view('revistas.index', compact('coleccion', 'pagina'));
    }

    public function show(Revista $revista)
    {
        /* página contenedora */

        $pagina = Pagina::getPaginaBySlug('revistas');

        /*if (!Auth::user()) {
            return view('auth.login', compact('pagina'));
        }*/
        return view('revistas.show', compact('revista', 'pagina'));
    }

    /**
     * Recuperar los registros de prensa que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Prensa
     */
    public function listaRevistas(Request $request)
    {
        $usuario = $this->usuario;
        return Revista::filtrados($request, $usuario);
    }
}
