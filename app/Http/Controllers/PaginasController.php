<?php

namespace App\Http\Controllers;

use App\UsuarioSocio;
use Illuminate\Http\Request;

use App\Pagina;
use App\Role;
use App\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use TCG\Voyager\Voyager;
// añadido para enviar el email de contacto (mail y validator)
use App\Mail\ContactoWeb;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
// para montar la página para preview
use App\Menu;
use App\Pastilla;
use App\Partesgrafica;
use App\Slider;
use App\MenuItemPagina;
// cookie del pop-up temporal
// use Cookie;
use App\PaginaCodificada;


class PaginasController extends Controller
{
    //
    private const HASH_METHOD = 'aes-128-ecb';
    private const HASH_KEY = 'vzW+V<@9}{8R-fYC';

    public function index()
    {

        // recuperar la home o la primera
        $rol_institucional = Role::rolInstitucional()->id;
        $pagina = Pagina::getHomeVisible($rol_institucional);

        // si no hay home, recuperamos cualquiera -> 404 si no hay ninguna
        if (!$pagina) {
            $pagina = Pagina::rol($rol_institucional)->visible()->first() ?? abort(404);
        }
        return view('paginas.show', compact('pagina'));
    }

    // mostrar página según "slug"
    public function showChatbot()
    {
        // Si el modo mantenimiento está activo mostrar página en mantenimiento
        if (setting('site.mantenimiento')) {
            return view('paginas.mantenimiento');
        }
        
        $pagina = Pagina::getPaginaBySlug('cribado-cancer-colon/chatbot-1');
        if (!$pagina) return abort(404);
        // END acceso restringido a través de enlace

        return $this->viewPageByModel($pagina, false);
    }

    public function show($pagina)
    {
        // Si el modo mantenimiento está activo mostrar página en mantenimiento
        if (setting('site.mantenimiento')) {
            return view('paginas.mantenimiento');
        }

        // 3 Ways - Alexis Bogado
        // Acceso a contenido restringido a través de enlace
        $pagina_codificada = PaginaCodificada::find($pagina);
        if ($pagina_codificada && $pagina_codificada->habilitado) :
            $pagina = $pagina_codificada->pagina;
        else :
            $pagina = Pagina::getPaginaBySlug($pagina);
        endif;

        if (!$pagina) return abort(404);
        // END acceso restringido a través de enlace

        return $this->viewPageByModel($pagina, $pagina_codificada);
    }

    public function viewPageByModel ($pagina, $pagina_codificada)
    {
        $parametrosVista = [];
        $parametrosVista['pagina'] = $pagina;
        $parametrosVista['pagina_codificada'] = ($pagina_codificada ? true : false);

        // para la HOME, controlar si hay usuario conectado y cambiar entre home pública/privada
        switch ($pagina->slug) {
            case 'inicio':
                /////////////////////////////////////////////////////////////////////////////////
                // gestión Cookie pop-up temporal
                // ELIMINAR JUNTO EL USE del inicio
                /////////////////////////////////////////////////////////////////////////////////
                // chequear la cookie y añadir el parámetro para la vista
                /**
                 * 3ways Euro Fuenmayor
                 *  Agregado intervalo desde 21/12 del año en curso hasta el 06/01 del siguiente año
                 *  para activar modal con mensaje de navidad.
                 *  Nombre de cookie generico independiente del año en curso
                 */
                $cook = Cookie::get('sepd_navidad_2021');
                //$current_year = now()->year;
                //if ($cook !== 'on' && (date("Ymd") > $current_year . "1219") && (date("Ymd") < strval(intval($current_year) + 1) . "0107")) {
                    Cookie::queue('sepd_navidad_2021', 'on', 24 * 60 + 7);
                    $parametrosVista['felicitacion_navidad'] = 'on';
                //} else {
                    //$parametrosVista['felicitacion_navidad'] = 'off';
                //}

                /////////////////////////////////////////////////////////////////////////////////
                // FIN gestión Cookie pop-up temporal
                // ELIMINAR JUNTO EL USE del inicio
                /////////////////////////////////////////////////////////////////////////////////

                // si está conectado, redirigir a inicio-usuarios
                if (Auth::user()) $parametrosVista['pagina'] = Pagina::where('slug', 'inicio-usuarios')->first();
                break;
                // para la página home privada no hace falta nada, ya que se redirige a través de rutas
                // case 'inicio-usuarios':
                //    break;
            case 'privacidad':
                // cambiar la miga de pan
                $parametrosVista['miga_pan'] = '> Privacidad';
                break;
        }

        // controlar si el contenido es retringido
        if (!$pagina_codificada && $pagina->acceso == 'restringido' && !Auth::user()) {
            $vista = 'auth.login';
        } else if ($pagina->slug == 'manual_empleado' || $pagina->slug == 'comunicados') {
            if (!Auth::user() || !Auth::user()->es_socio()) {
                return redirect('/hazte_socio');
            } else {
                $vista = 'paginas.show';
            }
        } else if ($pagina->slug == 'cribado-cancer-colon/chatbot-1') {
            $vista = 'paginas.chatbot-1';
        } else {
            $vista = 'paginas.show';
        }

        $parametrosVista['sso_token'] = null;

        if (Auth::check()) {
            $user = auth()->user();
            if ($user) {
                $socio = UsuarioSocio::where('usuario_id', auth()->id())->first();
                if ($socio) {
                    $encrypted = $this->encrypt([
                        'mail' => $user->email,
                        'nombre' => $user->nombre,
                        'apellidos' => $user->apellidos,
                        'origen' => 'sepd'
                    ]);
                    $parametrosVista['sso_token'] = urlencode($encrypted);
                }
            }
        }
        
        return view($vista)->with($parametrosVista);
    }

    /**
     * Encrypt an array
     *
     * @param array $str
     *
     * @return string
     */
    function encrypt(array $array)
    {
        return openssl_encrypt(json_encode($array), self::HASH_METHOD, self::HASH_KEY);
    }

    /**
     * Decrypt a encrypted string
     *
     * @param string $encryptedStr
     *
     * @return string
     */
    function decrypt($encryptedStr)
    {
        return openssl_decrypt($encryptedStr, self::HASH_METHOD, self::HASH_KEY);
    }

    // mostrar la página de contacto
    public function showContacto()
    {
        config()->set('captcha.sitekey', env('NOCAPTCHA_SITEKEY'));
        config()->set('captcha.secret', env('NOCAPTCHA_SECRET'));

        $pagina = Pagina::getPaginaBySlug('contacto');
        // sin miga de pan
        $miga_pan = '-';

        return view('contacto.index', compact('pagina', 'miga_pan'));
    }

    // mostrar el mapa web
    public function showMapa()
    {

        $pagina = Pagina::getPaginaBySlug('mapa_web');
        // sin miga de pan
        $miga_pan = '-';

        return view('mapa.show', compact('pagina', 'miga_pan'));
    }

    // validar datos de contacto
    public function validatorContacto(array $data)
    {
        config()->set('captcha.sitekey', env('NOCAPTCHA_SITEKEY'));
        config()->set('captcha.secret', env('NOCAPTCHA_SECRET'));

        return Validator::make($data, [
            'nombre' => 'required|string|max:255',
            'email_contacto' => 'required|email',
            'asunto' => 'required|string|min:6',
            'mensaje' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }

    // validar datos de contacto y enviar Email con el contacto
    public function sendEmailContacto(Request $request)
    {
        // validar los datos de formulario de contacto
        $validator = $this->validatorContacto($request->all());

        $validator->validate();

        $loadedAt = session('contact_form_loaded_at');

        if ($loadedAt && now()->diffInSeconds($loadedAt) < 30) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Debe esperar unos 30 segundos antes de enviar otro mensaje.',
            ]);
        }

        // Enviar el mensaje con copia al usuario y a sepd@sepd.es
        Mail::to($request->email_contacto)
            ->bcc(setting('site.email_contacto'))
            ->queue(new ContactoWeb(array(
                'nombre' => $request->nombre,
                'asunto' => $request->asunto,
                'mensaje' => $request->mensaje,
            )));


        $pagina = Pagina::getPaginaBySlug('contacto');

        $enviado = true;

        session(['contact_form_loaded_at' => now()]);

        Log::info('Formulario de contacto enviado', [
            'ip'        => $request->ip(),
            'userAgent' => $request->userAgent(),
            'inputs'    => $request->except(['g-recaptcha-response', '_token']),
            'email'     => $request->email_contacto,
        ]);

        return view('contacto.index', compact('pagina', 'enviado'));
    }

    // Devuelve una colección de pastillas para montar el preview de una página
    // Utilizada en preview()
    // @param array de Pastillas del objeto preview (normales o de contenido_extra)
    // @return collection of Pastilla
    protected function getColeccionPastillas($arrayPastillas)
    {

        $listaPastillas = [];

        // para cada pastilla generar el objeto pastilla y meterlo en el array resultante
        foreach ($arrayPastillas as $pastilla) {
            // recuperar la pastilla o crear una pastilla vacía
            $pastillaActual = (($pastilla->id > 0) ? Pastilla::find($pastilla->id) :  new Pastilla());
            // Se debe discernir si estamos en páginas o en pastillas:
            //  - en páginas no hace falta procesar las partes gráficas
            //  - en pastillas sí hace falta
            //  En pastillas se recibe el campo formato, en páginas no.
            if (isset($pastilla->formato)) {
                // actualizar los datos, exceptuando partesgraficas y pivot que tienen gestión específica posterior
                $camposPastillaArray = (array) $pastilla;
                $camposPastilla = collect($camposPastillaArray)->except(['pivot', 'partesgraficas'])->toArray();
                $pastillaActual->fill($camposPastilla);
                // hay que cargar las partes gráficas, si tenía
                $coleccionPartesgraficas = new \Illuminate\Database\Eloquent\Collection();
                $pastillaActual->partesgraficas = $coleccionPartesgraficas;
                foreach ($pastilla->partesgraficas as $pg) {
                    // recuperar la parte gráfica de la BD y sus datos pivot.
                    // Solo modificaremos los datos PIVOT si es que se han modificado.
                    // Los datos pivot se recuperan en $partegrafica->pastillas[0]->pivot
                    $partegrafica = Partesgrafica::with([
                        'pastillas' => function ($query) use ($pastilla) {
                            $query->where('pastilla_id', $pastilla->id);
                        }
                    ])->find($pg->id);
                    // Si llegan datos pivot, machacar lo que había
                    // Si no se había tocado los datos pivot (abrir la ventana con el icono del lápiz), el objeto llega con todo vacío (null)
                    if (isset($pg->pivot) && isset($pg->pivot->partesgrafica_id) && $pg->pivot->partesgrafica_id) {
                        $partegrafica->pivot = $pg->pivot;
                    } elseif (isset($partegrafica->pastillas[0])) {
                        // pero puede ser que la parte gráfica justo se haya añadido, y por tanto la BD no la contiene
                        // hay que controlar si en la BD había algo. Si no, se deja tal cual.
                        $partegrafica->pivot = $partegrafica->pastillas[0]->pivot;
                    } else {
                        // valores pivot por defecto para pastilla-partegrafica
                        $partegrafica->pivot = Partesgrafica::pivot_pastillas_vacio();
                    }
                    // y la metemos en la colección de partes gráficas de la pastilla
                    $pastillaActual->partesgraficas->push($partegrafica);
                }
                // guardar el núm de columnas (del formato). En páginas ya se recuperará de la página
                //                $columnas_pastillas = preg_replace("/[^0-9]/", "", $pastilla->formato);
            }
            // Tanto para páginas como pastillas!, sobrescribir la parte pivot de la pastilla
            $pastillaActual->pivot = $pastilla->pivot;
            // incluirla en la lista
            $listaPastillas[] = $pastillaActual;
        }

        return collect($listaPastillas);
    }

    // preview de páginas desde el backend (restringido por IP (middleware ipsepd))
    public function preview(Request $request)
    {

        // Crear una página y rellenar los datos con lo que se recibe
        $pagina = new Pagina();
        // request->pagina contiene todos los datos (algunos estarán ausentes según el elemento a previsualizar)
        // convertirlo en objeto para manipulación
        $objeto = json_decode($request->pagina);
        // Menu: si está vacío (objeto sin propietario todavía) && usuario admin -> institucional
        if ($objeto->menu_id) {
            $pagina->menu_id = $objeto->menu_id;
        } elseif ($objeto->role_id == Role::rolAdmin()->id) {
            $pagina->menu_id = Menu::getMenuInstitucional()->id;
        } else {
            $pagina->menu_id = Menu::getMenuByRolId($objeto->role_id)->id;
        }
        // Miga de Pan: si está vacío, la primera opción del menú
        $pagina->menu_item_id = ($objeto->menu_item_id) ?: MenuItem::getPrimerItemMenu($pagina->menu_id)->id;
        // Slider o Slider+Imagen
        if (isset($objeto->slider) && !empty($objeto->slider)) {
            // dos supuestos: - es un integer -> FK a slider desde páginas
            //                - es un objeto -> preview de slider, proceso especial
            if (ctype_digit($objeto->slider)) {
                $pagina->tipo_slider = $objeto->tipo_slider;
                $sliderActual = Slider::find($objeto->slider);
                $pagina->slider = $sliderActual;
            } else {
                // recuperar la pastilla o crear una pastilla vacía
                $sliderActual = (($objeto->slider->id > 0) ? Slider::find($objeto->slider->id) :  new Slider());
                // actualizar los datos (de hecho, es solo el formato)
                $sliderActual->formato = $objeto->slider->formato;
                // cargar las partes gráficas del $objeto
                // Como con pastillas, solo se sobrescribirán los datos PIVOT, si los hay
                $coleccionPartesgraficas = new \Illuminate\Database\Eloquent\Collection();
                $sliderActual->partesgraficas = $coleccionPartesgraficas;
                foreach ($objeto->slider->partesgraficas as $pg) {
                    // recuperar la parte gráfica de la BD y sus datos pivot.
                    // Solo modificaremos los datos PIVOT si es que se han modificado.
                    // Los datos pivot se recuperan en $partegrafica->slider[0]->pivot
                    $slider_id = $objeto->slider->id;
                    $partegrafica = Partesgrafica::with([
                        'sliders' => function ($query) use ($slider_id) {
                            $query->where('slider_id', $slider_id);
                        }
                    ])->find($pg->id);
                    // Si llegan datos pivot, machacar lo que había
                    // Si no se había tocado los datos pivot (abrir la ventana con el icono del lápiz), el objeto llega con todo vacío (null)
                    if (isset($pg->pivot) && isset($pg->pivot->partesgrafica_id) && $pg->pivot->partesgrafica_id) {
                        $partegrafica->pivot = $pg->pivot;
                    } elseif (isset($partegrafica->sliders[0])) {
                        // pero puede ser que la parte gráfica justo se haya añadido, y por tanto la BD no la contiene
                        // hay que controlar si en la BD había algo. Si no, se deja tal cual.
                        $partegrafica->pivot = $partegrafica->sliders[0]->pivot;
                    } else {
                        // valores pivot por defecto para slider-partegrafica
                        $partegrafica->pivot = Partesgrafica::pivot_sliders_vacio();
                    }
                    // y la metemos en la colección de partes gráficas de la pastilla
                    $sliderActual->partesgraficas->push($partegrafica);
                }

                // al final, asignar el slider a la página, pero "mapeando" el formato
                switch ($sliderActual->formato) {
                    case "s-3":
                        $pagina->tipo_slider = "3s";
                        break;
                    case "s-3A":
                        $pagina->tipo_slider = "3sA";
                        break;
                    case "s-2":
                        $pagina->tipo_slider = "2s+1i";
                        break;
                    case "s-2A":
                        $pagina->tipo_slider = "2sA+1iA";
                        break;
                    case "s-4":
                        $pagina->tipo_slider = "3s+1i";
                        break;
                }
                $pagina->slider = $sliderActual;
            }
        }
        // parte gráfica, si hay slider 2+1, 3+1 o solo imagen
        if (isset($objeto->imagen) && !empty($objeto->imagen)) {
            // recuperar la imagen de la base de datos. En este caso no hay pivot!!
            // guardar tipo_slider (por si era el caso de SOLO IMAGEN
            $pagina->tipo_slider = $objeto->tipo_slider;
            $partegraficaActual = Partesgrafica::find($objeto->imagen);
            $pagina->partesgrafica = $partegraficaActual;
        }
        // Pastillas: comprobar si llega algo en el objeto
        if (isset($objeto->pastillas) && !empty($objeto->pastillas)) {
            // convertir la lista de pastillas de objeto en pastillas para la página
            $pagina->pastillas = $this->getColeccionPastillas($objeto->pastillas);
            // Recuperar el número de columnas de pastillas: si es página viene como parámetro; si no, del formato de la primera pastilla
            $pagina->columnas_pastillas = (isset($objeto->columnas_pastillas)) ? $objeto->columnas_pastillas : preg_replace("/[^0-9]/", "", $pagina->pastillas[0]->formato);
            // pero si es pastilla formato libre, estará vacío -> por defecto 3
            ($pagina->columnas_pastillas == "") ? $pagina->columnas_pastillas = 3 : null;
        }
        // Destacados:
        if (isset($objeto->destacados) && !empty($objeto->destacados)) {
            // posicion: antes o después del contenido
            $pagina->posicion_destacados = $objeto->posicion_destacados;
            // para cada destacado recuperar el objeto menuitem y modificar los datos pivot (si es necesario)
            foreach ($objeto->destacados as $destacado) {
                // crear el elemento y leer el menu-item, la partegrafica y los datos pivot
                $menuItemPagina = new MenuItemPagina();
                // recuperar los datos pivot y actualizar
                $camposPivotDestacadoArray = (array) $destacado->pivot;
                $camposPivotDestacado = collect($camposPivotDestacadoArray)->except(['pagina_id'])->toArray();
                $menuItemPagina->fill($camposPivotDestacado);
                // recuperar los datos del MenuItem
                $menuItemPagina->menuitem = MenuItem::find($destacado->id);
                // si hay partegrafica, recuperarla
                if ($destacado->pivot->partesgrafica_id) {
                    $menuItemPagina->partesgrafica = Partesgrafica::find($destacado->pivot->partesgrafica_id);
                }
                // y meterlo en la lista de destacados
                $pagina->destacados[] = $menuItemPagina;
            }
        }
        // Contenido -> si hay contenido es preview de página
        if (isset($objeto->contenido)) {
            $pagina->contenido = $objeto->contenido;
            $pagina->contenido_extra = $objeto->contenido_extra;
            $pagina->contenido_extra_flotante = $objeto->contenido_extra_flotante;
            $pagina->columnas_extra = $objeto->columnas_extra;
            $pagina->posicion_extra = $objeto->posicion_extra;
            // pastillas de contenido
            // mismo procedimiento que con pastillas normales
            if (isset($objeto->pastillas_contenido) && !empty($objeto->pastillas_contenido)) {
                // convertir la lista de pastillas de objeto en pastillas para la página
                $pagina->pastillas_contenido = $this->getColeccionPastillas($objeto->pastillas_contenido);
            }
        }
        $parametrosVista['pagina'] = $pagina;
        // esto deberá hacer una serie de gestiones extra para páginas "programadas", por ejemplo, contacto
        return view('paginas.show')->with($parametrosVista);
    }

    // A ELIMINAR CUANDO SE INTEGREN COMPLETAMENTE LAS SECCIONES CID y FORMACION EN LA WEB ACTUAL
    // redirigir de www.sepd.es o sepd.es a www1.sepd.es (para CID y FORMACION)
    public function redirect_web_antigua(Request $request)
    {

        // comprobar si hay www.sepd.es
        if (strpos($request->fullUrl(), 'www.sepd.es') !== false) {
            $url_destino = str_replace('www.sepd.es', 'www1.sepd.es', $request->fullUrl());
        } else {
            $url_destino = str_replace('sepd.es', 'www1.sepd.es', $request->fullUrl());
        }
        // las páginas de formación debe ir SIN HTTPS
        if (strpos($url_destino, '/formacion/') !== false && strpos($url_destino, 'https') !== false) {
            $url_destino = str_replace('https', 'http', $url_destino);
        }

        return redirect($url_destino);
    }
}
