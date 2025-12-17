<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use App\Noticia;
use App\PodcastFead;
use App\Prensa;
use App\Documento;

class Buscador extends Model
{
    /**
     * Boot para el model -> GlobalScope.
     *
     * @return void
     */
    protected $dates = ['fecha'];

    protected $appends = ['fecha_formateada'];
    
    public static function search(?string $search = null, ?string $search_category = null)
    {
        if ($search=='')
            $search = ' ';

        $resultado = collect([]);
        $categories = collect([]);
        $search_busqueda = str_replace(' ', '%', $search);

        // Noticias
        $noticias = Noticia::where('titulo','LIKE',"%{$search_busqueda}%")->orWhere('texto', 'LIKE', '%'. $search_busqueda .'%')->get();
        $categoryResult = self::formatter($noticias, 'Noticias');

        if (is_null($search_category) || $search_category == 'Noticias')
            $resultado = $resultado->concat($categoryResult);
        
        $categories = $categories->push((object) ['category' => 'Noticias', 'total' => $noticias->count()]);
        
        // Podcast
        $podcasts = PodcastFead::where('title','LIKE',"%{$search_busqueda}%")->orWhere('description', 'LIKE', '%'. $search_busqueda .'%')->get();
        $categoryResult = self::formatter($podcasts, 'Podcast');

        if (is_null($search_category) || $search_category == 'Podcast')
            $resultado = $resultado->concat($categoryResult);
        
        $categories = $categories->push((object) ['category' => 'Podcast', 'total' => $podcasts->count()]);
    
        // Prensa
        $prensa = Prensa::where('titulo','LIKE',"%{$search_busqueda}%") ->orWhere('texto', 'LIKE', '%'. $search_busqueda .'%')->get();
        $categoryResult = self::formatter($prensa, 'Prensa');

        if (is_null($search_category) || $search_category == 'Prensa')
            $resultado = $resultado->concat($categoryResult);
        
        $categories = $categories->push((object) ['category' => 'Prensa', 'total' => $prensa->count()]);

        // Documento
        $biblio_documento = Documento::where('titulo','LIKE',"%{$search_busqueda}%") ->orWhere('autor', 'LIKE', '%'. $search_busqueda .'%')->orWhere('etiquetas', 'LIKE', '%'. $search .'%')->orWhere('descripcion', 'LIKE', '%'. $search .'%')->get();
        $categoryResult = self::formatter($biblio_documento, 'Biblioteca');

        if (is_null($search_category) || $search_category == 'Biblioteca')
            $resultado = $resultado->concat($categoryResult);
        
        $categories = $categories->push((object) ['category' => 'Biblioteca', 'total' => $categoryResult->count()]);

        // Pages
        $paginas = Pagina::where('contenido','LIKE',"%{$search_busqueda}%")->Where('acceso', '=', 'publico')->get();
        $categoryResult = self::formatter($paginas, 'Paginas');

        if (is_null($search_category) || $search_category == 'Paginas')
            $resultado = $resultado->concat($categoryResult);
        
        $categories = $categories->push((object) ['category' => 'Paginas', 'total' => $categoryResult->count()]);

        // Curso
        // $cursos = Curso::where('titulo','LIKE',"%{$search_busqueda}%")->orWhere('descripcion', 'LIKE', '%'. $search_busqueda .'%')->orWhere('etiquetas', 'LIKE', '%'. $search .'%')->get();
        // $resultado = $resultado->concat(self::formatter($cursos, 'Cursos'));
        // $categories = $categories->concat((object) ['category' => 'Cursos', 'total' => $cursos->count()]);

        $resultado = $resultado->sortByDesc('created_at');

        $page = request()->get('page', 1);

        $perPage =  setting('site.elementos_pagina');

        $offset = ($page * $perPage) - $perPage;

        return [
            'paginator' => new LengthAwarePaginator
            (array_slice($resultado->toArray(), $offset, $perPage, true),
                $resultado->count(),
                $perPage,
                $page,
                ['path' => url(Paginator::resolveCurrentPath() . '?search='.$search)]
            ),
            'categories' => $categories->sortByDesc('total'),
        ];
    }

    private static function formatter ($items, $slug)
    {
        switch ($slug) {
            case 'Podcast':
                return $items->map(function($item) use ($slug) {
                        $created_at = ($item->updated_at ?? $item->created_at) ? Carbon::parse($item->updated_at ?? $item->created_at) : null;
                        return (object) [
                            'title' => $item->title,
                            'description' => self::limpiarTexto($item->texto),
                            'slug' => $slug,
                            'url' => self::makeUrl($item, $slug),
                            'category' => $slug,
                            'created_at' => $created_at ?? null,
                            'created_formater' => $created_at ? $created_at->format('Y-m-d') : null,
                        ];
                    })
                    ->filter(function($item) {
                        return $item->url !== null;
                    });
                break;
            case 'Noticias':
                return $items->map(function($item) use ($slug) {
                    $created_at = ($item->fecha) ? Carbon::parse($item->fecha) : null;
                    return (object) [
                        'title' => $item->titulo,
                        'description' => self::limpiarTexto($item->texto),
                        'slug' => $slug,
                        'url' => self::makeUrl($item, $slug),
                        'category' => $slug,
                        'created_at' => $created_at ?? null,
                        'created_formater' => $created_at ? $created_at->format('Y-m-d') : null,
                    ];
                    // $item->texto = strip_tags(mb_substr($item->texto, 0, 400)).'...';
                    // $item->contador = substr_count(strtolower($item->texto), strtolower($string));
                })
                ->filter(function($item) {
                    return $item->url !== null;
                });
                break;
            case 'Biblioteca':
                return $items->map(function($item) use ($slug) {
                    $created_at = ($item->updated_at ?? $item->created_at) ? Carbon::parse($item->updated_at ?? $item->created_at) : null;
                    return (object) [
                        'title' => $item->titulo,
                        'description' => self::limpiarTexto($item->descripcion),
                        'slug' => $slug,
                        'url' => self::makeUrl($item, $slug),
                        'category' => $slug,
                        'created_at' => $created_at ?? null,
                        'created_formater' => $created_at ? $created_at->format('Y-m-d') : null,
                    ];
                })
                ->filter(function($item) {
                    return $item->url !== null;
                });
                break;
            case 'Prensa':
                return $items->map(function($item) use ($slug) {
                    $created_at = ($item->updated_at ?? $item->created_at) ? Carbon::parse($item->updated_at ?? $item->created_at) : null;
                    return (object) [
                        'title' => $item->titulo,
                        'description' => self::limpiarTexto($item->texto),
                        'slug' => $slug,
                        'url' => self::makeUrl($item, $slug),
                        'category' => $slug,
                        'created_at' => $created_at ?? null,
                        'created_formater' => $created_at ? $created_at->format('Y-m-d') : null,
                    ];
                })
                ->filter(function($item) {
                    return $item->url !== null;
                });
                break;
            case 'Paginas':
                return $items->map(function($item) use ($slug) {
                    $created_at = ($item->updated_at ?? $item->created_at) ? Carbon::parse($item->updated_at ?? $item->created_at) : null;
                    return (object) [
                        'title' => $item->nombre,
                        'description' => self::limpiarTexto(strip_tags($item->contenido)),
                        'slug' => $slug,
                        'url' => self::makeUrl($item, $slug),
                        'category' => $slug,
                        'created_at' => $created_at ?? null,
                        'created_formater' => $created_at ? $created_at->format('Y-m-d') : null,
                    ];
                })
                ->filter(function($item) {
                    return $item->url !== null;
                });
                break;
            default:
                return collect([]);
        }
    }

    private static function makeUrl($item, $slug): ?string
    {
        switch ($slug) {
            case 'Paginas':
                return '/'.$item->slug;
            case 'Noticias':
                return '/noticias/'.$item->id;
            case 'Podcast':
                return '/podcast-feads/?search='.$item->title . '#listaPodcast';
            case 'Biblioteca':
                $file = optional(json_decode($item->archivo_biblio))[0];

                if ($file && $file->download_link)
                    return asset('storage/' . $file->download_link);

                return null;
            case 'Prensa':
                $jsonData = json_decode($item->all_file);

                $file = (is_array($jsonData) ? $jsonData[0]->download_link : $item->all_file);
                
                return config('app.url') . '/storage/' . $file;
            default:
                return null;
        }
    }

    public static function buscadorSepd($search)
    {
        if ($search=='')
            $search = ' ';

        $search_busqueda = str_replace(' ','%',$search);
        // Query para obtener en noticias el texto que se desea buscar
        $noticias = \App\Noticia::where('titulo','LIKE',"%{$search_busqueda}%") ->orWhere('texto', 'LIKE', '%'. $search_busqueda .'%')->get();
        //completo el array
        $result_noticias = self::completaArray($noticias, 'institucional', $search);
        //Transformo de std a array para poder utilizar la paginación de LengthAwarePaginator
        $resultado['noticias'] = json_decode(json_encode($result_noticias), True);

        // Query para obtener en calendario el texto que se desea buscar
        $calendario = \App\Calendario::where('titulo','LIKE',"%{$search_busqueda}%") ->orWhere('texto', 'LIKE', '%'. $search_busqueda .'%')->get();
        //completo el array
        $result_calendario = self::completaArray($calendario, 'calendario', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['calendario'] = json_decode(json_encode($result_calendario), True);

        // Query para obtener en prensa el texto que se desea buscar
        $prensa = \App\Prensa::where('titulo','LIKE',"%{$search_busqueda}%") ->orWhere('texto', 'LIKE', '%'. $search_busqueda .'%')->get();
        //completo el array
        $result_prensa = self::completaArray($prensa, 'prensa', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['prensa'] = json_decode(json_encode($result_prensa), True);

         // Query para obtener en paginas el texto que se desea buscar
        $paginas = \App\Pagina::where('contenido','LIKE',"%{$search_busqueda}%")->Where('acceso', '=', 'publico')->get();
        //completo el array
        $result_paginas = self::completaArray($paginas, 'institucional_paginas', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['paginas'] = json_decode(json_encode($result_paginas), True);
       
        // Query para obtener en empleo el texto que se desea buscar
        $empleo = \App\Empleo::where('titulo','LIKE',"%{$search}%") ->orWhere('texto', 'LIKE', '%'. $search .'%')->get();
        //completo el array
        $result_empleo = self::completaArray($empleo, 'institucional_empleo', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['empleo'] = json_decode(json_encode($result_empleo), True);


        $biblio_documento = \App\Documento::where('titulo','LIKE',"%{$search_busqueda}%") ->orWhere('autor', 'LIKE', '%'. $search_busqueda .'%')->orWhere('etiquetas', 'LIKE', '%'. $search .'%')->orWhere('descripcion', 'LIKE', '%'. $search .'%')->get();
        //completo el array
        $result_biblio = self::completaArray($biblio_documento, 'formacion', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['biblio'] = json_decode(json_encode($result_biblio), True);
        
        
     /*   $sepd_tv = \App\Sepdtv::where('titulo','LIKE',"%{$search}%") ->orWhere('subtitulo', 'LIKE', '%'. $search .'%')->orWhere('descripcion', 'LIKE', '%'. $search .'%')->orWhere('descripcion', 'LIKE', '%'. $search .'%')->get();
        //completo el array
        $result_tv = self::completaArray($sepd_tv, 'publicaciones', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['sepd_tv'] = json_decode(json_encode($result_tv), True);*/
        
        $cursos = \App\Curso::where('titulo','LIKE',"%{$search}%")->orWhere('descripcion', 'LIKE', '%'. $search .'%')->orWhere('etiquetas', 'LIKE', '%'. $search .'%')->get();
        //completo el array
        $result_cursos = self::completaArray($cursos, 'formacion_cursos', $search);
        //Transformo de std a array para poder utilizar la paginación de \Illuminate\Contracts\Pagination\LengthAwarePaginator::
        $resultado['cursos'] = json_decode(json_encode($result_cursos), True);
        
        
        //Juntamos los arrays
        $resultado = (array_merge($resultado['noticias'], $resultado['calendario'], $resultado['prensa'],$resultado['paginas'], $resultado['empleo'],  $resultado['biblio'], $resultado['cursos']));
        
        //Ordenamos según el número de ocurrencias del texto
        $resultado_ordenado = self::orderMultiDimensionalArray($resultado, 'contador');

        $page = request()->get('page', 1); // Get the ?page=1 from the url
        // $page = Input::get('page', 1); // Get the ?page=1 from the url
        $perPage =  setting('site.elementos_pagina'); // Number of items per page
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator
            (array_slice($resultado_ordenado, $offset, $perPage, true), // Only grab the items we need
                count($resultado_ordenado), // Total items
                $perPage, // Items per page
                $page, // Current page
                ['path'=>url(Paginator::resolveCurrentPath().'?search='.$search)]
            );
    }
    
    /**
     * Función para completar el array de búsqueda, hay que psarle el array y el slug para poder enlazar al elemento
     * Añade el slug para el ver másF
     * Formatea el texto para que sólo muestre 400 caracteres
     * Cuenta las apariciones de la cadena buscada para ordenar por número de apariciones
     */
    public static function completaArray($array_busqueda, $slug, $string) {

        foreach ($array_busqueda as $k => $valor) {
            
            if ($slug == 'institucional_paginas') {
               
               // páginas
               $valor->titulo = $valor->nombre; 
               $valor->url = $valor->slug; 
               $valor->contenido = $valor->descripcion;
               $valor->slug = "institucional";
               
            } else {
                $valor->slug = $slug;
            }
            
            if ($slug == 'institucional') {
                $valor->url = '/noticias/'.$valor->id; 
            }
            
            if ($slug == 'formacion') {
                $valor->id = $valor->id_documento; 
            }
            
            if ($slug=='formacion_cursos'){
                $valor->slug = 'formacion';
                $valor->url = config('app.url').'/cursos/'.$valor->id;
            }
            
            if ($slug=='calendario'){
                if ($valor->role_id) {
                    $valor->slug =  Role::getNombreRol($valor->role_id);
                } else {
                    $valor->slug =  Role::getNombreRol(Role::rolInstitucional()->id);
                }
                
                $valor->url = '/calendario/'.$valor->id;
            }
            
            if ($slug=='institucional_empleo'){
                $valor->slug =  'institucional';
                $valor->url = '/empleos/'.$valor->id;
            }
            
            if ($slug=='prensa'){
                $jsonData = json_decode($valor->all_file);
                $file = (is_array($jsonData) ? $jsonData[0]->download_link : $valor->all_file);

                $valor->url = config('app.url') . '/storage/prensa/' . $file; 
            }
           
            $count = substr_count(strtolower($valor->texto), strtolower($string));
            // $count = mb_substr_count( $noticia->texto, "sepd"); 
            $valor->contador =  $count + mb_substr_count( strtolower($valor->titulo), strtolower($string));
            // $valor->contador = $count ;
            $valor->texto = strip_tags(mb_substr($valor->texto, 0, 400)).'...';
        }
        
        return $array_busqueda;
    } 
    
    
    /**
     * Ordena un array en función del campo
     */
    public static function orderMultiDimensionalArray ($ArrayDesordenado, $campo) {  
        $result = array();
        $claves = array();
        //Guardamos en el array $claves los indices y el campo que queremos ordenar
        foreach ($ArrayDesordenado as $clave => $fila){
          $claves[$clave] = $fila[$campo];
        }
        //Ordenamos el array por el contenido, que es el campo que hemos elegido.
        arsort($claves);
        //recorremos el array de claves ya ordenado y vamos rellenando un nuevo array
        //con los campos completos con el nuevo orden
        //Recorremos el array de claves ordenadas y rellenamos de nuevo nuestro array
        foreach ($claves as $clave => $fila){
          $result[] = $ArrayDesordenado[$clave];
        }
        return $result;
    }

    public static function limpiarTexto($texto) {
        // Reemplazar los títulos de sección
        $texto = preg_replace('/@@@TituloSeccion#(.*?)@@@/', "\n\n$1\n", $texto);

        // Reemplazar los títulos de página
        $texto = preg_replace('/@@@TituloPagina#(.*?)@@@/', "\n$1\n", $texto);

        // Reemplazar &nbsp; por un espacio
        $texto = str_replace('&nbsp;', ' ', $texto);

        // Quitar múltiples espacios o saltos de línea
        $texto = preg_replace('/\s{2,}/', ' ', $texto);
        $texto = preg_replace("/\n{2,}/", "\n", $texto);

        // Limpiar espacios innecesarios
        $texto = trim($texto);

        return $texto;
    }
}

