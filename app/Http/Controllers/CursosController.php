<?php

namespace App\Http\Controllers;

use App\Curso;
use App\Pagina;
use App\MenuItem;
use App\User;
use App\ProyectoFormativo;
use Auth;

use Illuminate\Http\Request;

class CursosController extends Controller
{

    public function index($tipo = null)
    {

        /* recuperar la colección de cursos según el tipo */
        switch ($tipo) {
            case 'acreditados':
                $coleccion = Curso::orderBy('fecha_inicio', 'DESC')->acreditados();
                break;
            case 'multimedia':
                $coleccion = Curso::orderBy('fecha_inicio', 'DESC')->multimedia();
                break;
            case 'gastromir':
                $coleccion = Curso::orderBy('fecha_inicio', 'DESC')->gastroMIR();
                break;
            case 'mis-cursos':
                // solo para usuarios registrados!
                if (!Auth::user()) {
                    return view('auth.login', compact('pagina', 'migaPan', 'nombre_menu'));
                } else {
                    $coleccion = Curso::orderBy('fecha_inicio', 'DESC')->misCursos();
                }
                break;
            default:
                if (!Auth::user() || !Auth::user()->es_socio()) {
                    $coleccion = Curso::orderBy('fecha_inicio', 'DESC')->whereNull("proyecto_formativo_id")->paginate(setting('site.elementos_pagina'));
                    foreach ($coleccion as $key => $curso) {
                        if ($curso->configuracion(5)) {
                            unset($coleccion[$key]);
                        }
                    }
                     
                } else {
                    $coleccion = Curso::orderBy('fecha_inicio', 'DESC')->whereNotNull("proyecto_formativo_id")->paginate(setting('site.elementos_pagina'));
                }
                $proyectos = ProyectoFormativo::orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($proyecto) {
                    return (object)[
                        'id' => "proyecto?id=".$proyecto->id,
                        'titulo' => $proyecto->nombre,
                        'ruta_imagen' => '',
                        'fecha_formateada' => $proyecto->created_at->format('d/m/Y'),
                        'formato' => 'proyecto',
                        'descripcion' => '',
                        'acreditado' => false,
                        'descripcion_estado' => 'Proyecto formativo',
                        'descripcion_contenido' => null,
                        'configuracion_socios' => false,
                        'creditos' => 0,
                        'fecha_fin' => null,
                    ];
                });
                break;
        }
        
        //$coleccion = $proyectos->concat(collect($coleccion->items()));
        /* página contenedora */
        $pagina = Pagina::getPaginaBySlug('cursos');

        $tis = ($tipo == 'tis');

        return view('cursos.index', compact('coleccion', 'tis', 'pagina'));
    }

    /**
     * Recuperar los cursos que cumplen unos criterios de búsqueda (recibidos en $request)
     * 
     * API
     * 
     * @param Request $request
     * @return collection Cursos
     */
    public function listaCursos(Request $request)
    {

        return Curso::filtrados($request);
    }
    /** FORMACIÓN: 3Ways - Carlos Colmenarez
     * Mostrar vista curso mostrar (publico)
     *
     * @return void
     */
    public function mostrar(Curso $id)
    {
        $pagina = Pagina::getPaginaBySlug('cursos');

        $user = Auth::user();

        if ($user && $user->usuario_cursos->where('curso_id', $id->id)->first()) {
            return redirect()->route('curso.hacer', $id);
        }
        
        return view('cursos.mostrar', [
            'curso' => $id,
            'pagina' => $pagina
        ]);
    }
    /** 3Ways - Carlos Colmenarez
     * Mostrar vista curso hacer (usuario)
     *
     * @return void
     */
    public function hacer(Curso $id)
    {
        $pagina = Pagina::getPaginaBySlug('mis_cursos');

        $user = Auth::user();
        
        $usuario_curso = $user->usuario_cursos->where('curso_id', $id->id)->first();
        if (!$usuario_curso) return redirect()->route('curso.mostrar', $id);

        // 3 Ways - Alexis Bogado
        // Si el usuario tiene el diploma en 'usuarios_diplomas'
        // pero no tendría que tenerlo se elimina el registro
        // ---------------------------------------------------
        // Si el usuario no tiene diploma en 'usuarios_diplomas'
        // pero debería tenerlo, se agrega el registro
        $usuario_diploma = $user->usuario_diplomas->where('curso_id', $id->id)->first();
        if ($usuario_diploma && !$usuario_curso->obtiene_diploma()) $usuario_diploma->delete();
        elseif (!$usuario_diploma && $usuario_curso->obtiene_diploma()) DiplomasController::comprobar_diploma($usuario_curso, $usuario_diploma);
        $curso = Curso::find($id->id);

        return view('cursos.hacer', [
            'curso' => $id,
            'pagina' => $pagina,
            'usuario_curso' => $usuario_curso,
            'usuario_diploma' => $usuario_diploma,
            'is_strict' =>  $curso->is_strict,
            'user' => $user
        ]);
    }
    /** 3Ways - Carlos Colmenarez
     * Mostrar catalogo de cursos (usuario-publico)
     *
     * @return void
     */
    public function catalogo()
    {
        $pagina = Pagina::getPaginaBySlug('cursos');

        $cursos = Curso::disponibles()->orderByDesc('id')->paginate(5);
        
        return view('cursos.catalogo', compact('cursos', 'paginacion', 'pagina'));
    }

    public function proyectos ($id){
        $proyecto = ProyectoFormativo::findOrFail($id);

        $cursos = Curso::where('proyecto_formativo_id', $id)
                       ->orderBy('fecha_inicio', 'desc')
                       ->paginate(10);

        return view('cursos.proyectos', compact('proyecto', 'cursos'));
    }
}
