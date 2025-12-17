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
Route::get('revistas', 'RevistaController@listaRevistas');
Route::get('noticias', 'NoticiaController@listaNoticia');
Route::get('sepdtv', 'SepdtvController@listaSepdtv');
Route::get('galeria', 'GaleriaController@listaGaleria');
Route::get('carpeta_galeria', 'GaleriaController@getNombreCarpeta');
Route::get('biblioteca', 'BibliotecaController@listaBiblioteca');
Route::get('empleos', 'EmpleoController@listaEmpleo');
// suma una reproducción de un vídeo
Route::post('reproduccion/{video}', 'SepdtvController@sumaReproduccion');

// decrypt desde el gestor de administración
// A ELIMINAR CUANDO SE INTEGRE EL GESTOR DE SOCIOS EN EL BACKEND!!!!
// A ELIMINAR TAMBIÉN LAS FUNCIONES DE LOS CONTROLADORES CORRESPONDIENTES
Route::get('socio_s/{uid}', 'SocioController@getTarjetaSolicitud');
// A ELIMINAR CUANDO SE INTEGRE EL GESTOR DE SOCIOS EN EL BACKEND!!!!
// A ELIMINAR TAMBIÉN LAS FUNCIONES DE LOS CONTROLADORES CORRESPONDIENTES
