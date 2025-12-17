<?php

namespace App;

use App\MenuItem;
// para el formateo de fechas en español (fecha update)
use Jenssegers\Date\Date;

class Pagina extends Model
{
    protected $with = ['menu', 'slider', 'partesgrafica', 'pastillas', 'destacados', 'pastilla', 'pastillas_contenido'];
    // Accessors:
    protected $appends = ['fecha_formateada', 'miga_pan'];
    
    /***************************************
     * WITH ..... relaciones de página
     *
     **************************************/

    /**
     * Datos del menú asociado: @return Menu
     */
    public function menu() {
        return $this->belongsTo('App\Menu');
    }
    
    /**
     * Datos del slider asociado: @return Slider
     */
    public function slider() {
        return $this->belongsTo('App\Slider');
    }

    /**
     * Datos de la imagen asociada: @return PartesGrafica
     */
    public function partesgrafica() {
        return $this->belongsTo('App\Partesgrafica');
    }

    /**
     * Datos de las pastillas asociadas: @return Pastilla
     */
    public function pastillas() {
        $pivot_columns = [
            'orden',
            'texto',
            'texto_cabecera',
            'texto_boton',
            'enlace',
            'destino_enlace'
        ];
        return $this->belongsToMany('App\Pastilla')->withPivot($pivot_columns)->orderBy('pagina_pastilla.orden');
    }

    /**
     * Datos de los "destacados": @return Menu_item
     */
    public function destacados() {
        return $this->hasMany('App\MenuItemPagina')->orderBy('menu_item_pagina.orden');
    }

    // A ELIMINAR, ESTA PARTE HA SIDO SUSTITUIDA POR PASTILLAS_CONTENIDO
    // El atributo pastilla_id es el que está relacionado, FK también hay que eliminarla
    /**
     * Datos del banner (pastilla) asociado al contenido: @return Pastilla
     */
    public function pastilla() {
        return $this->belongsTo('App\Pastilla');
    }
    // A ELIMINAR, ESTA PARTE HA SIDO SUSTITUIDA POR PASTILLAS_CONTENIDO
    

    /**
     * Datos de las pastillas del contenido: @return Pastilla
     */
    public function pastillas_contenido() {
        $pivot_columns = [
            'orden',
            'texto',
            'texto_cabecera',
            'texto_boton',
            'enlace',
            'destino_enlace'
        ];
        return $this->belongsToMany('App\Pastilla', 'pagina_pastilla_contenido')->withPivot($pivot_columns)->orderBy('pagina_pastilla_contenido.orden');
    }

    /****************************
     * QUERY SCOPES
     *
     ***************************/

    /**
     * Query Scope para devolver solo páginas marcadas como visibles
     */
    public function ScopeVisible($query) {
        
        return $query->where('visible', '1');

    } 
    
    /**
     * Query Scope para devolver solo las HOMES!
     */
    public function ScopeHome($query) {
        
        return $query->where('home', '1');

    }
    
    /**
     * Query Scope para devolver todas las páginas de un rol
     */
    public function ScopeRol($query, $rol) {

        return $query->where('role_id', $rol);

    }

    /***************************************************
     * Atributos calculados!
     *
     ***************************************************/

    /**
     * Montar un array de columnas con las pastillas dentro
     * El orden de las pastillas (pagina_pastilla.orden) es:
     * ARRIBA HACIA ABAJO e IZQUIERDA A DERECHA
     * o sea, por columnas
     * 
     * @return array of columnas de pastillas
     */
    public function getPastillasEnColumnasAttribute()
    {
        // 2 arrays para el contenido final
        $columnas = array(); // resultado final
        $contenido_columna = array(); // columna actual

        // controlar el total de columnas y el total de espacio de pastillas
        $total_espacio = 0;
        foreach ($this->pastillas as $pill) {
            (strpos($pill->formato,'doble')>0) ? $total_espacio += 2 : $total_espacio++;
        }

        $espacio_por_columna = floor($total_espacio/$this->columnas_pastillas);
        $espacio_esta_columna = 0;

        // recorrer el array de pastillas e ir rellenando
        foreach ($this->pastillas as $pill) {
            // meter la pastilla en la columna
            $contenido_columna[] = $pill;
            // controlar el espacio por columna
            (strpos($pill->formato,'doble')>0) ? $espacio_esta_columna += 2 : $espacio_esta_columna++;
            if ($espacio_esta_columna >= $espacio_por_columna) {
                // cambiar de columna
                $columnas[] = $contenido_columna;
                $contenido_columna = array();
                $espacio_esta_columna = 0;
            }
        }

        return $columnas;
    }

    /**
     * Hay alguna pastilla doble en la página?
     * Necesario para formatear las pastillas en la vista
     * @return boolean
     */
    public function getAlgunaPastillaDobleAttribute() {
        
        foreach ($this->pastillas as $pill) {
            if (strpos($pill->formato,'doble')>0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Miga de Pan de la página (recuperada de la opción de menú de la página)
     * 
     * @return string
     */
    public function getMigaPanAttribute() {
        
        return MenuItem::getMigaPan($this->menu_item_id);

    }

    /**
     * Miga de Pan de la página (recuperada de la opción de menú de la página)
     * 
     * @return string
     */
    public function getListaIdsActivosAttribute() {
        
        return MenuItem::getListaIdsActivos($this->menu_item_id);

    }

    /**
     * Devuelve la fecha del dossier formateada en español
     * @return string
     */
    public function getFechaFormateadaAttribute() {
        
        Date::setLocale('es');
        
        $fecha = new Date($this->updated_at);
        $fecha_txt = ucfirst($fecha->format('j F Y'));
        
        return $fecha_txt;
    }

    /***************************************************
     * Otras funciones dedicadas, normalmente static
     *
     ***************************************************/

    /**
     * Página de inicio de un rol
     */
    public static function getHomeVisible($role_id) {

        return static::visible()->home()->where('role_id', $role_id)->first();

    }

    /**
     * Recuperar página via slug
     */
    public static function getPaginaBySlug($slug) {

        $pagina= static::where('slug', $slug)->first();
        
        return $pagina;

    }
    
    /**
     * Recuperar la página anterior: url()->previous()
     */
    public static function getPaginaAnterior() {

        // recuperar la URL y de ahí el 'slug'
        $url = url()->previous();
        $slug = substr($url, strrpos($url, "/") + 1);
        
        return static::getPaginaBySlug($slug);

    }
    
}
