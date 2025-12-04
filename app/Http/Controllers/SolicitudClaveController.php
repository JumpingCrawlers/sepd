<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Curso;
use App\UsuarioCursoSolicitud;

class SolicitudClaveController extends Controller {

    /* 3 Ways - Alexis Bogado */

    public function _Construct() {
        $this->middleware('auth');
    }

    /**
     * Enviar solicitud de clave
     * 
     * @return void
     */
    public function index($id) {
        $user = Auth::user();

        $curso = Curso::findOrFail($id);

        if ($user->solicito_clave($curso->id)) return abort(404);
        if ($user->usuario_cursos->where('curso_id', $curso->id)->count() > 0) return abort(404);
        if ($curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria' || !$curso->registroAbierto()) return abort(404);
        if (!$curso->configuracion(4)) return abort(404);

        $this->storeSolicitud($user->id, $curso->id);
        return back()->with([
            'key-success' => '¡La solicitud de clave se ha enviado correctamente!'
        ]);
    }

    /**
     * Guardar solicitud en base de datos
     * 
     * @param Integer $userId
     * @param Integer $cursoId
     * 
     * @return void
     */
    public function storeSolicitud($userId, $cursoId) {
        $usuarioCursoSolicitud = new UsuarioCursoSolicitud;
        $usuarioCursoSolicitud->usuario_id = $userId;
        $usuarioCursoSolicitud->curso_id = $cursoId;

        $usuarioCursoSolicitud->save();
    }

}
