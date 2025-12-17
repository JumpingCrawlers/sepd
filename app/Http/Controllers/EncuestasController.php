<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Curso;
use App\EncuestaPregunta;
use App\UsuarioEncuesta;
use App\UsuarioEncuestaRespuesta;
use App\Pagina;

class EncuestasController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    // Mostrar encuesta
    public function mostrar($id) {
        $pagina = Pagina::getPaginaBySlug('mis_cursos');

        $user = Auth::user();

        $usuario_curso = $user->usuario_cursos->where('curso_id', $id)->first();
        if (!$usuario_curso || !$usuario_curso->obtiene_diploma()) return redirect()->route('curso.hacer', [ $id ]);

        $curso = $usuario_curso->curso;
        if (!$curso->encuesta || $user->encuesta($curso)->count() > 0) return redirect()->route('curso.hacer', [ $id ]);

        return view('formacion.encuestas.mostrar', compact('curso', 'pagina'));
    }

    // Enviar encuesta
    public function enviarRespuestas($id, Request $request) {
        $pagina = Pagina::getPaginaBySlug('mis_cursos');

        $user = Auth::user();

        $usuario_curso = $user->usuario_cursos->where('curso_id', $id)->first();
        if (!$usuario_curso || !$usuario_curso->obtiene_diploma()) return redirect()->route('curso.hacer', [ $id ]);
        
        $curso = $usuario_curso->curso;
        if (!$curso->encuesta || $user->encuesta($curso)->count() > 0) return redirect()->route('curso.hacer', [ $id ]);

        $respuestas = [ ];
        $error = false;
        foreach ($request->input() as $key => $respuesta):
            if (strpos($key, "preg_") === false) continue;
            $encuesta_pregunta = EncuestaPregunta::find(intval(substr($key, 5)));
            if (!$encuesta_pregunta) continue;
            // Averiguamos si la pregunta tiene respuestas y su valor es nullo salta
            // En caso contrario es una pregunta abierta
            if((count($encuesta_pregunta->respuestas()->get()) > 0) && is_null($respuesta)) continue;
            $respuestas[$key] = $respuesta;
        endforeach;

        if (count($respuestas) < $curso->encuesta->preguntas->where('deleted_at', null)->count())
            return redirect()->back()->with('error-message', 'Debes responder todas las preguntas para finalizar la encuesta.');

        $usuario_encuesta = $this->storeEncuesta($curso);
        foreach ($curso->encuesta->preguntas->where('deleted_at', null) as $pregunta)
            $this->storeRespuesta($pregunta->id, $usuario_encuesta->id, $respuestas["preg_{$pregunta->id}"]);

        // El cliente ha cambiado de opinion y quiere que al finalizar la encuesta redirija a la pagina de ficha directamente
        //return view('formacion.encuestas.finalizada', compact('curso', 'pagina'));
        return redirect()->route('curso.hacer', ['id' => $id]);
    }

    // Insertar datos en usuarios_encuestas
    public function storeEncuesta($curso) {
        $user = Auth::user();

        $usuario_encuesta = new UsuarioEncuesta;
        $usuario_encuesta->encuesta_id = $curso->encuesta_id;
        $usuario_encuesta->curso_id = $curso->id;
        $usuario_encuesta->usuario_id = $user->id;
        $usuario_encuesta->save();

        return $usuario_encuesta;
    }

    // Insertar respuesta en usuarios_encuestas_respuestas
    public function storeRespuesta($pregunta_id, $usuario_encuesta_id, $respuesta) {
        $usuario_encuesta_respuesta = new UsuarioEncuestaRespuesta;
        $usuario_encuesta_respuesta->encuesta_pregunta_id = $pregunta_id;
        $usuario_encuesta_respuesta->usuarios_encuestas_id = $usuario_encuesta_id;
        $usuario_encuesta_respuesta->respuesta = $respuesta;
        $usuario_encuesta_respuesta->save();
    }
}
