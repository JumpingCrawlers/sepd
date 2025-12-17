<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('cursos', 'CursosController@listaCursos');
Route::get('consultas', 'ConsultasController@listaConsultas');
Route::get('proyectos', 'ProyectosController@listaProyectos');
Route::get('dossier', 'RepercusionController@listaDossier');
Route::get('prensa', 'PrensaController@listaPrensa');
Route::get('calendario', 'CalendarioController@listaEventos');
Route::get('calendario-investigacion', 'CalendarioController@listaEventosInvestigacion');
Route::middleware(['web', 'auth'])->get('revistas', 'RevistaController@listaRevistas');
Route::get('noticias', 'NoticiaController@listaNoticia');
Route::get('sepdtv', 'SepdtvController@listaSepdtv');
Route::get('galeria', 'GaleriaController@listaGaleria');
Route::get('carpeta_galeria', 'GaleriaController@getNombreCarpeta');
Route::get('biblioteca', 'BibliotecaController@listaBiblioteca');
Route::middleware(['web'])->get('empleos', 'EmpleoController@listaEmpleo');
//Route::get('empleos', 'EmpleoController@listaEmpleo');
Route::get('podcast-feads', 'PodcastFeadController@listaPodcastFeads');
// suma una reproducción de un vídeo
Route::post('reproduccion/{video}', 'SepdtvController@sumaReproduccion');

Route::post('send_vpcr/{id}/{user_id}', 'VpcrController@sendMail');

//devuelve la configuracion de las gestion de configuracion de las cookies
Route::get('cookies_config', function () {
    return response()->json(config('cookie-consent'));
});

Route::get('manualdigestivosepd/diploma', 'DiplomasController@manualDigestivoSepdDiploma');
Route::get('memory', function () {
    // Memoria usada por PHP en MB
    $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2);

    // Memoria total del servidor en MB
    $memTotalLine = shell_exec("grep MemTotal /proc/meminfo");
    preg_match('/\d+/', $memTotalLine, $matches);
    $memoryTotal = isset($matches[0]) ? round($matches[0] / 1024, 2) : null;

    // Memoria libre en MB
    $memFreeLine = shell_exec("grep MemFree /proc/meminfo");
    preg_match('/\d+/', $memFreeLine, $matches);
    $memoryFree = isset($matches[0]) ? round($matches[0] / 1024, 2) : null;

    // CPU info (modelo y cores)
    $cpuModel = shell_exec("grep 'model name' /proc/cpuinfo | head -1");
    $cpuCores = trim(shell_exec("nproc"));

    return response()->json([
        'memoryUsageMB' => $memoryUsage,
        'memoryTotalMB' => $memoryTotal,
        'memoryFreeMB'  => $memoryFree,
        'cpuModel'      => trim($cpuModel),
        'cpuCores'      => (int) $cpuCores,
    ]);
});

Route::get('phpinfo', function () {
    return phpinfo();
});
// decrypt desde el gestor de administración
// A ELIMINAR CUANDO SE INTEGRE EL GESTOR DE SOCIOS EN EL BACKEND!!!!
// A ELIMINAR TAMBIÉN LAS FUNCIONES DE LOS CONTROLADORES CORRESPONDIENTES
// Route::get('socio_s/{uid}', 'SocioController@getTarjetaSolicitud');
// A ELIMINAR CUANDO SE INTEGRE EL GESTOR DE SOCIOS EN EL BACKEND!!!!
// A ELIMINAR TAMBIÉN LAS FUNCIONES DE LOS CONTROLADORES CORRESPONDIENTES
