<?php

namespace App;

// para el Global Scope
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;

class Calendario extends Model
{
    //
    protected $table = 'calendario';
    public $timestamps = false;
    // para marcar las instancias de Carbon
    protected $dates = [
        'fecha',
        'fecha_fin',
        'fecha_reg'
    ];
    
    // Accessors:
    protected $appends = ['fecha_formateada', 'ruta_imagen', 'seccion'];
    

    /***************************************************
     * GLOBAL SCOPE
     *
     ***************************************************/

    /**
     * Boot para el model -> GlobalScope.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Se filtran los eventos de los últimos y los próximos X meses
        $anteriores = 2;
        $posteriores = 12;
        
        $inicio = Carbon::now();
        $final = Carbon::now();

        $inicio->subMonthsNoOverflow($anteriores);
        $inicio->startOfMonth();

        $final->addMonthsNoOverflow($posteriores);
        $final->endOfMonth();
        
        static::addGlobalScope('calendario', function (Builder $builder) use ($inicio, $final) {
            $builder->where('fecha', '>=', $inicio)
                    ->where('fecha_fin', '<', $final);
        });
    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Montar el calendario con sus eventos
     * @return Calendar (fullcalendar.io)
     */
    public static function calendario() {

        // array de eventos para FullCalendario
        $eventosFull = [];

        // recuperar de la base de datos todos los eventos a mostrar
        // El GlobalScope ya filtra todo (no importa el orden)
        $eventos = static::where(function($q) {
            $q->where('role_id', '!=', 5)
              ->orWhereNull('role_id');
        })->get();

        
        foreach ($eventos as $evento) {

            // controlar los colores (clase)
            $seccion = 'evento-'.$evento->seccion;
            
            // Si es para la página calendario => se necesita el ID
            // si está dentro de una pastilla no hay link
            $enlace = (strpos(url()->current(), route('calendario')) !== false) ? $evento->id : '';

            // añadir el evento al array
            $eventosFull[] = \Calendar::event(
                $evento->titulo,
                true, // siempre son del día entero
                $evento->fecha,
                $evento->fecha_fin->addDay(), // sumar un día a fecha final (full calendar lo muestra HASTA)
                    $evento->id, //optionally, you can specify an event ID
                    [
                        'url' => $enlace,
                        'className' => $seccion
                    ]
                 
            );
        }

        // cargar todos los eventos
        $calendar = \Calendar::addEvents($eventosFull)
                        ->setOptions([
                            'header' => ['left' => 'prev, next', 'right'=>'', 'center'=>'title'],
                            'firstDay' => 1,
                            'locale' => 'es'
                        ]);

        return $calendar;
        
    }
    
    /**
     * Montar el calendario con sus eventos investigación
     * @return Calendar (fullcalendar.io)
     */
    public static function calendarioInvestigacion() {

        // array de eventos para FullCalendario
        $eventosFull = [];

        // recuperar de la base de datos todos los eventos a mostrar
        // El GlobalScope ya filtra todo (no importa el orden)
        $eventos = static::where('role_id', 5)->get();

        
        foreach ($eventos as $evento) {

            // controlar los colores (clase)
            $seccion = 'evento-'.$evento->seccion;
            
            // Si es para la página calendario => se necesita el ID
            // si está dentro de una pastilla no hay link
            $enlace = (strpos(url()->current(), route('calendario')) !== false) ? $evento->id : '';

            // añadir el evento al array
            $eventosFull[] = \Calendar::event(
                $evento->titulo,
                true, // siempre son del día entero
                $evento->fecha,
                $evento->fecha_fin->addDay(), // sumar un día a fecha final (full calendar lo muestra HASTA)
                    $evento->id, //optionally, you can specify an event ID
                    [
                        'url' => $enlace,
                        'className' => $seccion
                    ]
                 
            );
        }

        // cargar todos los eventos
        $calendar = \Calendar::addEvents($eventosFull)
                        ->setOptions([
                            'header' => ['left' => 'prev, next', 'right'=>'', 'center'=>'title'],
                            'firstDay' => 1,
                            'locale' => 'es'
                        ]);

        return $calendar;
        
    }

    /*****************************************************************
     * ACCESSORS: Atributos calculados
     * 
     *
     *****************************************************************/

    /**
     * Devuelve la fecha del dossier formateada en español
     * @return string
     */
    public function getFechaFormateadaAttribute() {
        
        Date::setLocale('es');

        $fecha = new Date($this->fecha);
        $fecha_fin = new Date($this->fecha_fin);

        // control de la fecha fin
	if ($fecha->format('dmY') === $fecha_fin->format('dmY')) {
            // mismo día
            $fecha_txt = ucfirst($fecha->format('j F Y'));
	}
	elseif ($fecha->format('mY') === $fecha_fin->format('mY')) {
            // mismo mes
            $fecha_txt = ucfirst($fecha->format('j').'-'.$fecha_fin->format('j F Y'));
	}
	elseif ($fecha->format('Y') === $fecha_fin->format('Y')) {
            // mismo año
            $fecha_txt = ucfirst($fecha->format('j F').' - '.$fecha_fin->format('j F Y'));
	}
	else {
            // todo diferente
            $fecha_txt = ucfirst($fecha->format('j F Y').' - '.$fecha_fin->format('j F Y'));
	}

        return $fecha_txt;
    }
    
    /**
     * Ruta completa de la imagen
     * @return string
     */
    public function getRutaImagenAttribute() {
     
        return  Voyager::image($this->imagen);
    }

    /**
     * Nombre de la sección a la que pertenece el evento
     * @return string (global | externo | nombreRol)
     */
    public function getSeccionAttribute() {
    
        if ($this->externo) {
            return 'externo';
        } elseif ($this->role_id > 0) {
            return Role::getNombreRol($this->role_id);
        }

        return  'global';
    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Lista de notas de prensa filtrados según parámetros del request
     * @return collection Prensa
     */
    public static function filtrados($request) {

        // Montamos la query sin filtros
        $query = self::withoutGlobalScopes();
        $eventos = static::where(function($q) {
            $q->where('role_id', '!=', 5)
              ->orWhereNull('role_id');
        })->get();

        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    // filtro texto, filtrar texto + filtrar evento futuro
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('titulo', 'like', '%' . $valor . '%')
                                          ->orWhere('texto', 'like', '%' . $valor . '%')
                                          ->orWhere('lugar', 'like', '%' . $valor . '%');
                             })
                             ->where('fecha_fin', '>', date('Y-m-d'));
                    break;
                 case "id":
                    // filtro id, detalle evento sin filtro fecha
                    $query->where('id', '=', $valor);
                    break;
                default:
                    // llamada inicial, sin parámetros -> eventos futuros
                    $query = $query->where('fecha_fin', '>', date('Y-m-d'));
                    break;
            }

        }
        
        // ordenar por fecha inicio
        $query->orderBy('fecha');

        return $query->paginate(setting('site.elementos_pagina'));

    }


    /**
     * Lista de notas de prensa filtrados según parámetros del request y por role_id = 5 (investigacíón)
     * @return collection Prensa
     */
    public static function filtradosInvestigacion($request) {

        // Montamos la query sin filtros
        $query = self::withoutGlobalScopes();
        
        $query->where('role_id', 5);

        
        foreach ($request->all() as $filtro => $valor) {
            // los parámetros tienen el id en el nombre -> recuperarlo
            $variable = preg_split('/_/',$filtro);
            
            switch (strstr($filtro, "_", true)) {
                case "texto":
                    // filtro texto, filtrar texto + filtrar evento futuro
                    $query = $query->where(function ($query) use ($valor) {
                                    $query->where('titulo', 'like', '%' . $valor . '%')
                                          ->orWhere('texto', 'like', '%' . $valor . '%')
                                          ->orWhere('lugar', 'like', '%' . $valor . '%');
                             })
                             ->where('fecha_fin', '>', date('Y-m-d'));
                    break;
                 case "id":
                    // filtro id, detalle evento sin filtro fecha
                    $query->where('id', '=', $valor);
                    break;
                default:
                    // llamada inicial, sin parámetros -> eventos futuros
                    $query = $query->where('fecha_fin', '>', date('Y-m-d'));
                    break;
            }

        }
        
        // ordenar por fecha inicio
        $query->orderBy('fecha');

        return $query->paginate(setting('site.elementos_pagina'));

    }
}
