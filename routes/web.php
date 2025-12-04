<?php

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------------------------------
| Rutas de autenticación (por defecto en vendor\laravel\framework\src\Illuminate\Routing\Router.php
| Para sobrescribir la de registro para que sea en castellano, se copian todas aquí
|--------------------------------------------------------------------------------------------------
*/


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return 'Cache cleared successfully!';
});

Route::get('/auth-login/{email}', function ($email) {
    $user = \App\User::where('email', $email)->first();
    if ($user) {
        \Auth::login($user);
        return redirect('/');
    } else {
        return abort(404);
    }
});

Route::get('aursos2/cursos-inscripcion-test-email-2', function () {
    $usuario_curso = \App\UsuarioCurso::first();
    
    $curso = \App\Curso::first();
    
    $usuario_pago = \App\UsuarioPago::first();
    
    $factura = \App\Factura::first();
    
    $factura = \PDF::loadView('base.factura', [
        'factura' => $factura,
        'code' => 'addada'
        ])->setOptions(['defaultFont' => 'sans-serif']);
        
        // return  $factura->download();
    try {
        $email = \Illuminate\Support\Facades\Mail::to('carlosanselmi2@gmail.com')->send(new  \App\Mail\CursoInscripcion($usuario_curso, $factura));
        return 'true';
    } catch (\Throwable $th) {
        return $th->getMessage();
    }

    return \Illuminate\Support\Facades\Mail::to('carlosanselmi2@gmail.com')->send(new  \App\Mail\CursoInscripcionAdmin($usuario_pago->user, $curso, $factura));

    return \Illuminate\Support\Facades\Mail::to('carlosanselmi2@gmail.com')
    ->queue(
        new \App\Mail\SolicitudSocio([
            'nombre' => 'carlos',
            'tipo' => 'tipo',
            'uid' => 'loprem',
        ])
    );
});


// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
// SEPD-OV: desde el sistema antiguo se hace una petición GET a logout.
//          El funcionamiento normal es solo POST con csrf token
Route::match(['get', 'post'], 'logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('registro', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('registro', 'Auth\RegisterController@register');
// Segundo paso de registro, una vez recuperados los datos del socio
Route::post('registro-confirmacion', 'Auth\RegisterController@confirmation')->name('register_paso2');
// Registro usuario
Route::get('registro-usuario', 'UserController@showRegistrationForm')->name('registroUsuario');
Route::post('registro-usuario', 'UserController@register');
Route::get('registro-usuario-preview', 'UserController@show_post_register');
// Pago online
Route::post('redsys', 'Auth\RedsysController@index')->name('tpv');
Route::get('tpv_respuesta', 'Auth\RedsysController@check')->name('tpv_respuesta');
Route::post('tpv_notification/{uid?}',  'Auth\RedsysController@confirm')->name('redsys_notification');

// Perfil de usuario
Route::get('perfil/editar', 'Auth\PerfilController@edit')->name('editar_perfil');
Route::get('perfil/{tab?}', 'Auth\PerfilController@show')->name('perfil');
Route::post('perfil', 'Auth\PerfilController@store');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@passwordResset')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

/*
|------------------------------------------------------------------------
| Rutas para las páginas "especiales", con gestión específica 
|------------------------------------------------------------------------
*/

/* Proceso de hacerse socio */
Route::get('/hazte_socio', 'SocioController@showTipoSocioForm')->name('hazte_socio');
Route::post('/hazte_socio', 'SocioController@saveTipoSocio');
Route::get('/hazte_socio/registro', 'SocioController@showRegistrationForm');
Route::post('/hazte_socio/registro', 'SocioController@validarDatosRegistro');

/* Para cursos */
/* 3Ways - Carlos Colmenarez */
//Vista Curso (publico)
Route::get('/cursos/{id}/', 'CursosController@mostrar')->where(['id' => '[0-9]+'])->name('curso.mostrar');
Route::get('/cursos/proyecto/{id}/', 'CursosController@proyectos')->where(['id' => '[0-9]+'])->name('cursos.proyectos');
//Vista Catalogo de Cursos orginal
Route::get('/cursos/{tipo?}', 'CursosController@index')->name('cursos.tipo');
//Vista Catalogo de Cursos temporal
Route::get('/cursos', 'CursosController@index')->name('cursos');


/* Para proyectos cid */
Route::get('/proyectos/{proyecto}', 'ProyectosController@show')->name('detalle_proyecto')->where('proyecto', '[0-9]+');
Route::get('/proyectos/{tipo?}', 'ProyectosController@index')->name('proyectos');

/* Para consultas cid */
Route::get('/consultas/{consulta}', 'ConsultasController@show')->name('detalle_consulta')->where('consulta', '[0-9]+');
Route::get('/consultas/{tipo?}', 'ConsultasController@index')->name('consultas');
Route::post('/consultas', 'ConsultasController@store')->name('consultas.nueva');

/* Para la biblioteca */
Route::get('/biblioteca/{area?}', 'BibliotecaController@index')->name('biblioteca');

/* Para la sepdTV */
Route::get('/sepdtv/{area?}', 'SepdtvController@index')->name('sepdtv');
Route::get('/sepdtv/video/{id}', 'SepdtvController@showVideo')->name('sepdtv_video');

/* Para las noticias */
Route::match(['get', 'post'], '/noticias/{noticia}', 'NoticiaController@show')->name('detalle_noticia')->where('noticia', '[0-9]+');
Route::match(['get', 'post'], '/noticias/{seccion?}', 'NoticiaController@index')->name('noticias');

/* Para los empleos */
Route::match(['get', 'post'], '/empleos/{empleo}', 'EmpleoController@show')->name('detalle_empleo')->where('empleo', '[0-9]+');
Route::match(['get', 'post'], '/empleos/{filtro?}', 'EmpleoController@index')->name('empleos');

/* Para prensa */
Route::match(['get', 'post'], '/prensa/{nota}', 'PrensaController@show')->name('detalle_prensa')->where('nota', '[0-9]+');
Route::match(['get', 'post'], '/prensa', 'PrensaController@index')->name('prensa');

/* Para el calendario */
Route::match(['get', 'post'], '/calendario/{evento?}', 'CalendarioController@index')->name('calendario')->where('evento', '[0-9]+');
Route::match(['get', 'post'], '/calendario-investigacion/{evento?}', 'CalendarioController@indexInvestigacion')->name('calendario_investigacion')->where('evento', '[0-9]+');
/* Para revistas INFO-SEPD y GI Hepatology */
Route::get('/info-sepd', 'RevistaController@index')->name('info-sepd');
Route::get('/gi-hepatology-news', 'RevistaController@index')->name('hepatology');
Route::get('/gastro-news/{revista}', 'RevistaController@show')->name('detalle_revista')->where('revista', '[0-9]+');
Route::get('/gastro-news', 'RevistaController@index')->name('gastro-news');
/* 3ways Euro Fuenamyor - agregado ruta para la pagina international-gastroenterology-news */
Route::get('/international-gastroenterology-news', 'RevistaController@index')->name('gastroenterology');

/* Para los podcasts */
//Route::match(['get', 'post'], '/podcasts', 'PodcastController@index')->name('podcasts');
Route::get('/podcast-feads/{podcast_feads_id?}', 'PodcastFeadController@index');
Route::get('/podcast', 'PodcastFeadController@index');
Route::get('/Escucha_SEPDigestiva', 'PodcastFeadController@index');
Route::get('/escucha--sepdigestiva', 'PodcastFeadController@index')->name('podcasts-fead');

/* Para la repercusión mediática */
Route::get('/presencia_medios', 'RepercusionController@index')->name('repercusion');

/* Para la página de contacto */
Route::get('/contacto', 'PaginasController@showContacto')->name('contacto');
Route::post('/contacto', 'PaginasController@sendEmailContacto');

/* para el buscador */
Route::match(['get', 'post'], '/buscar', 'BuscadorController@index')->name('buscador');

/* Para la galería */
Route::get('/galeria/{carpeta?}', 'GaleriaController@index')->name('galeria');

/* Para el mapa web */
Route::get('/mapa_web', 'PaginasController@showMapa')->name('mapa');

/* REDIRECCION TEMPORAL PARA LAS PAGINA CID y FORMACION */
/* A ELIMINAR CUANDO SE UNIFIQUEN LAS SECCIONES CID y FORMACION */
Route::get('/cid/{params?}', 'PaginasController@redirect_web_antigua')->where('params', '.*');
// Route::get('/formacion/{params}', 'PaginasController@redirect_web_antigua')->where('params', '.*');

/**
 * 3ways Euro Fuenmayor
 * Ajustado para redireccionar al url en función del valor asignado al parámetro url según la regla de ruta para /cid_servicios
 */
Route::get('/cid_servicios', 'ServiciosCidController@redirigirUrl')->name('cid_servicios');


/*
|------------------------------------------------------------------------
| Rutas para las páginas genéricas
|------------------------------------------------------------------------
*/
// Redireccionar la home a '/inicio' Fix #27
Route::redirect('/', '/inicio', 301)->name('home');
// Redireccionar la home privada a '/inicio'
Route::redirect('/inicio-usuarios', '/inicio', 301)->name('home.inicio');
// El resto de páginas, gestión por defecto
Route::get('/{str}', 'PaginasController@show')->name('pagina');
Route::get('/cribado-cancer-colon/chatbot-1', 'PaginasController@showChatbot');

/*
|------------------------------------------------------------------------
| Ruta de previsualización desde backend. Se filtra por IP
|------------------------------------------------------------------------
*/
Route::post('/preview', 'PaginasController@preview')->middleware('ipsepd');

Route::get("/login_user.php", function () {
    return Redirect::to("login_user.php");
});

/*
|------------------------------------------------------------------------
| Fallback para todas las otras rutas
|------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});

/*
|------------------------------------------------------------------------
| RUTAS FORMACIÓN
|------------------------------------------------------------------------
*/

// Route::get('/home', 'HomeController@index')->name('home');

//Vista diploma
// Route::get("/diploma/{id}", 'DiplomasController@mostrar')->name('diploma');
Route::get("/diploma/{id}", 'DiplomaController@show')->name('diploma');
Route::get("/diploma/download/{id}", 'DiplomasController@download')->name('diploma.download');

//Vista certificado VPCR
Route::get("/vpcrs/descargar", 'VpcrsController@mostrar')->name('certificado_vpcr');

// Pago curso
Route::post('/cursos/{id}/pago/respuesta/{uid}', 'CursoPagoController@respuesta')->name('curso.pago.respuesta');
Route::get('/cursos/{id}/pago/respuesta/{tipo}', 'CursoPagoController@respuestaTipo')->name('curso.pago.respuesta.tipo');

Route::group(['middleware' => ['auth']], function () {
    //Vista Aula Virtual
    Route::get('/formacion/aula', 'AulaController@index')->name('formacion.aula');
    //Vista Mis-Cursos
    Route::get('/formacion/mis-cursos/', 'MisCursosController@misCursos')->name('formacion.mis-cursos');
    //Vista Curso (usuario)
    Route::get('/formacion/cursos/{id}/', 'CursosController@hacer')->name('curso.hacer');
    Route::post('/formacion/cursos/{id}/', 'ItemController@usuarioTiempo');
    //Vista Inscripción Curso
    Route::get('/cursos/{id}/inscripcion', 'InscripcionesController@mostrar')->name('curso.inscripcion');
    Route::post('/cursos/{id}/inscripcion', 'InscripcionesController@accesoClave');
    // Solicitud clave
    Route::post('/cursos/{id}/solicitud-clave', 'SolicitudClaveController@index')->name('solicitud.clave');
    //Vista Pago Curso
    Route::get('/cursos/{id}/pago', 'CursoPagoController@index');
    //Cuestionario
    Route::get('/formacion/cuestionario/{id}', 'CuestionarioController@mostrar')->name('cuestionario');
    Route::post('/formacion/cuestionario/{id}', 'CuestionarioController@enviarRespuestas')->name('cuestionario.enviar');
    //Acreditaciones
    Route::get('formacion/acreditaciones/{tipo?}', 'AcreditacionesController@show')->name('formacion.acreditaciones')->where('tipo', 'online|presencial');
    //Vista Mensajes 
    Route::get('/cursos/{id}/tutorias/{usuario_id}', 'MensajesController@show')->name('mensajes.show');
    Route::get('/formacion/mensajes/', 'MensajesController@list')->name('mensajes.usuario');
    Route::post('/cursos/{curso}/receptores/{receptor}/store', 'MensajesController@store')->name('storeMensaje');
    //Video sincronizado (item diapositiva)
    Route::get('/formacion/diapositivas/{id}', 'DiapositivasController@index')->name('formacion.diapositivas');
    //Vista Encuesta
    Route::get('/formacion/cursos/{id}/encuesta', 'EncuestasController@mostrar')->name('formacion.encuesta');
    Route::post('/formacion/cursos/{id}/encuesta', 'EncuestasController@enviarRespuestas')->name('encuesta.enviar');
    // Vista calculadora
    Route::get('/formacion/calculadora', function () {
        if (!Auth::user()) {
            return redirect('/login');
        } else if (!Auth::user() || !Auth::user()->es_socio()) {
            return redirect('/hazte_socio');
        }
        $pagina = App\Pagina::getPaginaBySlug('calculadoras');
        return view('formacion.calculadora', compact('pagina'));
    })->name('formacion.calculadora');

    // Ruta de prueba
    Route::get('/formacion/pruebas_app', 'PruebasAppController@index');
    Route::get('/descargar/{type}/{model_id}', 'CertificadoSocioController@download')->name('download.certificado-socio');
});

// Validación diploma
Route::get('/formacion/validador-diploma', 'AcreditacionesController@searchDiploma');
Route::post('formacion/validador-diploma', 'AcreditacionesController@validarDiploma')->name('validar.diploma');

Route::get('vpc-r/menu_bar', function () {
    $pagina = App\Pagina::getPaginaBySlug('vpc-r');
    $nombre_menu = $pagina->menu->name;
    return view('puzzle.vpcr_menu', compact('pagina', 'nombre_menu'));
});
// Back -> Front Login
Route::post('login_by_id', 'UserController@index');

Route::get('test/email', 'MailController@testEmail')->name('test.email.get');
Route::post('test/email', 'MailController@postTestEmail')->name('test.email.post');

Route::get('envio/mail/curso-inscripcion/{email}', function ($email) {

    $usuario_curso = App\UsuarioCurso::find(1);

    $factura = App\Factura::find(100);

    \Mail::to($email)->send(new \App\Mail\CursoInscripcion($usuario_curso, $factura));
});

Route::get('envio/mail/curso-inscripcion-admin/{email}', function ($email) {

    $usuario_curso = App\UsuarioCurso::find(1);

    $factura = App\Factura::find(100);

    \Mail::to($email)->send(new \App\Mail\CursoInscripcionAdmin($usuario_curso->usuario,$usuario_curso, $factura));
});
Route::get('/certificados/descargar/{id}', 'Auth\PerfilController@descargarCertificado')
    ->name('certificados.descargar');


Route::get('/maintenance/down', function () {
    Artisan::call('down', ['--secret' => 'sepd-ov']);
    return 'Application is now in maintenance mode!';
});

Route::get('/maintenance/up', function () {
    Artisan::call('up');
    return 'Application is now live!';
});
