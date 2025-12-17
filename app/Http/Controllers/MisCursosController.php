<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UsuarioCurso;
use App\Pagina;

class MisCursosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Mostrar vista mis-cursos
     *
     * @return void
     */
    public function misCursos() {
        $pagina = Pagina::getPaginaBySlug('mis_cursos');

        $usuario=Auth::user();
        //Solo los cursos que esten publicados y no estén borrados    
        $usuario_cursos=UsuarioCurso::where('usuario_id', '=', $usuario->id)
                    ->join('cursos', 'cursos.id', '=', 'usuarios_cursos.curso_id')
                    ->whereNull('cursos.deleted_at')
                    ->orderByDesc('usuarios_cursos.id');
        if ($usuario_cursos->count()>0) {
            $usuario_cursos=$usuario_cursos->paginate(3);
            return view('formacion.mis-cursos', compact('usuario_cursos', 'pagina'));
        }
        else {
            return view('formacion.sin-cursos', compact('pagina'));
        }
    }
}
