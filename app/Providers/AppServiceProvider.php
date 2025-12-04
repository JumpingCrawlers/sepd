<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// para frase cabecera -> controlar si el usuario está conectado
use Illuminate\Support\Facades\Auth;
// para pasar el texto al buscador
use Illuminate\Http\Request;
use Twitter;
use Illuminate\Pagination\Paginator;

// Para añadir paginator a las colecciones
// Necesario para fusionar Documentos y Cursos en el resultado de biblioteca
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Paginator::useBootstrap();

        /**
         * Paginate a standard Laravel Collection. Copiado de:
         * https://gist.github.com/simonhamp/549e8821946e2c40a617c85d2cf5af5e
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        /**************************************************************************
         *  VIEW COMPOSERS para las diferentes páginas
         * ************************************************************************/
        
        /**
         * View Composer para el 404: se necesita un menú
         * 
         * Se intenta recuperar la página anterior y sacar de ahí el menú
         * Si no existe, devuelve el Institucional (parámetro site.menu_principal)
         * 
         */
        view()->composer(['errors::404', 'errors.default'], function($view) {

            $pagina = null;
            // Si no se había visitado nada antes...
            if (url()->previous() != config('app.url')) {
                // recuperar la página
                $pagina = \App\Pagina::getPaginaAnterior();
                // si había página, guarda su menú para posteriores 404
                ($pagina) ? session(['menu' => $pagina->menu->name]) : null;
            }
            // Si hay página, manda la página, pero si no, el menú se necesita
            $menu = session('menu', setting('site.menu_principal')); // por defecto, el menú principal

            $view->with([
                'pagina' => $pagina,
                'nombre_menu' => $menu
            ]);
        });

        /**
         * View Composer para las redes sociales
         * 
         * Se recupera todos los datos de los eventos disponibles
         * 
         */
        view()->composer('puzzle.master', function($view) {

            if ($flashMensaje = session('mensaje_flash')) {
                $flash['mensaje'] = $flashMensaje;
                $flash['tipo'] = 'mensaje';
            } elseif ($flashAlerta = session('alerta_flash')) {
                $flash['mensaje'] = $flashAlerta;
                $flash['tipo'] = 'alerta';
            } elseif($flashError = session('error_flash')) {
                $flash['mensaje'] = $flashError;
                $flash['tipo'] = 'error';
            } else {
                $flash = null;
            }

            $view->with('flash', $flash);

        });

        /**
         * View Composer para las frases de la cabecera
         * 
         * Controla si es institucional, y si el usuario está conectado
         * 
         */
        view()->composer('puzzle.cabecera', function($view) {
            // recuperar los datos de la página que se está mostrando
            $viewData = $view->getData();

            $pagina = optional($viewData)['pagina'];
            
            // si estamos en institucional, dependiendo de si hay una sesión iniciada se muestra una u otra frase en la cabecera
            // Excepción: 404, página es null, texto por defecto
            // En cualquier caso, necesitamos recuperar el rol institucional
            $rolInstitucional = \App\Role::rolInstitucional();

            if ($pagina) {
                // página institucional
                if ($pagina->menu->role->id == $rolInstitucional->id) {
                    (Auth::user()) ? $texto_cabecera = setting('site.frase_cabecera_privada') : $texto_cabecera = setting('site.frase_cabecera_publica');
                } else {
                    // página de sección
                    $texto_cabecera = $pagina->menu->role->display_name;
                }
                $css_cabecera = $pagina->menu->name;
            } else {
                // es un 404
                (Auth::user()) ? $texto_cabecera = setting('site.frase_cabecera_privada') : $texto_cabecera = setting('site.frase_cabecera_publica');
                $css_cabecera = $rolInstitucional->name;
            }

            $view->with(['texto_cabecera' => $texto_cabecera, 'css_cabecera' => $css_cabecera]);

        });

        /**
         * View Composer para las redes sociales
         * 
         * Se recupera todos los datos de los eventos disponibles
         * 
         */
        view()->composer('puzzle.redes_sociales', function($view) {

            $view->with('redes_sociales', \App\Partesgrafica::redes_sociales());

        });

        /**
         * View Composer para el calendario
         * 
         * Se recupera todos los datos de los eventos disponibles
         * 
         */
         view()->composer('puzzle.calendario', function($view) {

            $view->with('calendar', \App\Calendario::calendario());

        });
        
        /**
         * View Composer para últimas noticias
         * 
         */
         view()->composer('paginas.pastilla', function($view) {
            $noticias = \App\Noticia::lastNew()->get();

            // dd($noticias);

            $view->with('noticias', $noticias);

        });
        
            
        
        /**
         * View Composer para el buscador
         * 
         */
        view()->composer('buscador.resultados', function($view) use ($request) {

            $buscador = \App\Buscador::search($request->search, $request->search_category);

            $view->with('buscador', $buscador['paginator']);

            $view->with('search_categories', $buscador['categories']);
            // $view->with('buscador', \App\Buscador::buscadorSepd($request->search));

        });

        /**
         * View Composers de filtros: Areas
         * 
         * Recupera las áreas de elearning__biblio_area
         * 
         */
        view()->composer(['puzzle.filtros.cursos_areas', 'puzzle.filtros.proyectos_areas'], function($view) {

            $view->with('areas', \App\eBiblioArea::filtro());

        });

        /**
         * View Composers de filtros: AÑOS DE CURSOS
         * 
         * Recupera los años de elearning__curso
         * 
         */
        view()->composer('puzzle.filtros.cursos_anyos', function($view) {

            $view->with('anyos', \App\Curso::anyos());

        });

        /**
         * View Composers de filtros: AÑOS DE CURSOS
         * 
         * Recupera los años de elearning__curso
         * 
         */
        view()->composer('puzzle.filtros.podcast-feads_anyos', function($view) {

            $view->with('anyos', \App\PodcastFead::anyos());

        });
        
        /**
         * View Composers de filtros: AÑOS DE CURSOS
         * 
         * Recupera los años de elearning__curso
         * 
         */
        view()->composer('puzzle.filtros.podcast-feads_tipos', function($view) {

            $view->with('tipos', \App\PodcastFead::tipos());

        });

        /**
         * View Composers de filtros: Países de cursos
         * 
         * Recupera los paises según usado_cursos
         * 
         */
        view()->composer('puzzle.filtros.cursos_paises', function($view) {

            $view->with('paises', \App\Curso::paises());

        });

        /**
         * View Composers de filtros: AÑOS DE REPERCUSIÓN MEDIÁTICA
         * 
         * Recupera los años de DOSSIER
         * 
         */
        view()->composer('puzzle.filtros.dossier_anyos', function($view) {

            $view->with('anyos', \App\Dossier::anyos());

        });

        /**
         * View Composers de filtros: AÑOS DE PODCASTS
         * 
         * Recupera los años de PODCASTS
         * 
         */
        view()->composer('puzzle.filtros.podcasts_anyos', function($view) {

            $view->with('anyos', \App\Podcast::anyos());

        });
     
        /**
         * View Composers de filtros: lista formatos biblioteca
         * 
         * Recupera los formatos de BIBLIOTECA
         * 
         */
        view()->composer('puzzle.filtros.biblioteca_formatos', function($view) {

            $view->with('formatos', \App\Documento::formatos());

        });

        /**
         * View Composers de filtros: lista emedia biblioteca
         * 
         * Recupera los tipos de emedia de BIBLIOTECA
         * 
         */
        view()->composer('puzzle.filtros.biblioteca_emedia', function($view) {

            $view->with('emedia', \App\Documento::emedia());

        });

        /**
         * View Composers de filtros: lista años biblioteca
         * 
         * Recupera los años de BIBLIOTECA
         * 
         */
        view()->composer('puzzle.filtros.biblioteca_anyos', function($view) {

            // recuperar los de los documentos y los de los cursos
            $anyos_docs = array_map(
                    function ($array) {
                      unset($array['fecha_formateada']);
                      return $array;
                    },
                    \App\Documento::anyos()->toArray()
            );
            $anyos_cursos = array_map(
                    function ($array) {
                      unset($array['descripcion_estado']);
                      unset($array['fechas_formateadas']);
                      unset($array['fecha_formateada']);
                      return $array;
                    },
                    \App\Curso::anyos()->toArray()
            );

            
            // juntarlos y quitar duplicados
            $anyos = array_unique(array_merge($anyos_docs, $anyos_cursos),SORT_REGULAR);

            $anyos = collect($anyos)->unique('id')->values()->toArray();

            // ordenar descendiente
            usort($anyos, function ($a, $b) {
                return $a["id"] < $b["id"];
            } );
            // el último se etiqueta como Anteriores
            end($anyos);
            $indice = key($anyos);
            $anyos[$indice]['nombre'] = "Anteriores";
            
            // y pasarlo convertido en objeto!
            $view->with('anyos', json_decode(json_encode((object) $anyos), FALSE));

        });

        /**
         * View Composers de iconos: Iconos de tipos de documento
         * 
         * Recupera los iconos de los setting del backend para dossier
         * 
         */
        view()->composer(['repercusion.index', 'revistas.index','calendario.index', 'prensa.index', 'noticias.index', 'galeria.index', 'empleo.index', 'sepdtv.index', 'biblioteca.index'], function($view) {

            $view->with('iconos', \App\Setting::iconos());

        });

        /**
         * View Composers de filtros: AÑOS DE REPERCUSIÓN MEDIÁTICA
         * 
         * Recupera los años de PRENSA
         * 
         */
        view()->composer('puzzle.filtros.prensa_anyos', function($view) {

            $view->with('anyos', \App\Prensa::anyos());

        });

        /**
         * View Composers de filtros: AÑOS DE LAS NOTICIAS
         * 
         * Recupera los años de NOTICIAS
         * 
         */
        view()->composer('puzzle.filtros.noticias_anyos', function($view) {

            $view->with('anyos', \App\Noticia::anyos());

        });
        
        /**
         * View Composers de filtros: AÑOS DE REVISTAS
         * 
         * Recupera los años de REVISTAS
         * 
         */
        view()->composer('puzzle.filtros.revistas_anyos', function($view) {

            $view->with('anyos', \App\Revista::anyos());

        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
