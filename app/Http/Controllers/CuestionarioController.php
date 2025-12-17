<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cuestionario;
use App\UsuarioCuestionario;
use App\UsuarioCuestionarioRespuesta;
use App\CuestionarioPreguntaRespuesta;
use App\Pagina;

class CuestionarioController extends Controller
{

    //3 Ways - Carlos Colmenarez
    public function mostrar($id)
    {
        $pagina = Pagina::getPaginaBySlug('mis_cursos');

        $user = Auth::user();

        $cuestionario = Cuestionario::findOrFail($id);

        $oportunidades = $user->cuestionarios->where('cuestionario_id', $cuestionario->id)->count();

        $curso = $cuestionario->bloque->curso;

        $cuestionario_recuperacion_id = $this->getCustionarioRecuperacionId($cuestionario, $user);

        if (!$user->usuario_cursos->where('curso_id', $curso->id)->first())
            return abort(404);

        $usuario_cuestionario = $user->getLastCuestionarioById($cuestionario->id);

        if (!is_null($usuario_cuestionario)) {
            
            $intentos = ($oportunidades >= $cuestionario->oportunidades && $cuestionario->oportunidades > 0);

            if ($usuario_cuestionario->estado == "aprobado" || $intentos || ($user->tiempo_finalizado($curso) && $user->completo_cuestionario($usuario_cuestionario->cuestionario_id))) {

                $respuestas = [ "sin_contestar" => 0, "correctas" => 0, "incorrectas" => [ ] ];
                
                $usuario_respuestas = $usuario_cuestionario->usuario_respuestas();

                foreach ($usuario_respuestas as $key => $respuesta) {
                    $key++;
                    
                    if (is_null($respuesta->respuesta_id)) {
                        $respuestas["sin_contestar"]++;
                    } else {
                        
                        $pregunta_respuesta = CuestionarioPreguntaRespuesta::where('id', $respuesta->respuesta_id)->first();

                        if ($pregunta_respuesta->correcta != 1)
                            $respuestas["incorrectas"][] .= $cuestionario->preguntas->where('id', CuestionarioPreguntaRespuesta::find($respuesta->respuesta_id)->cuestionarios_preguntas_id)->first()->orden;
                        else
                            $respuestas["correctas"]++;
                    }
                }

                return view('cursos.cuestionario.resultado', compact('cuestionario', 'usuario_cuestionario', 'respuestas', 'pagina', 'oportunidades', 'cuestionario_recuperacion_id'));
            }                
        }

        if ($user->tiempo_finalizado($curso) || ($curso->configuracion(1) && !$user->completo_bloque($curso->bloque_anterior($cuestionario->bloque), $cuestionario->bloque))) {
            return abort(404);
        } elseif (($cuestionario->id_superior && $cuestionario->id_superior > 0 && $cuestionario->cuestionario_superior)) {
            if (is_null($cuestionario_recuperacion_id)) {
                if ((is_null($user->getLastCuestionarioById($cuestionario->cuestionario_superior->id)) || ($user->getLastCuestionarioById($cuestionario->cuestionario_superior->id)->estado == "aprobado") || ($user->cuestionarios()->where('cuestionario_id', $cuestionario->cuestionario_superior->id)->count() < $cuestionario->cuestionario_superior->oportunidades) || ($cuestionario->cuestionario_superior->oportunidades <= 0) || ($user->tiempo_finalizado($curso) && $user->completo_cuestionario($cuestionario->cuestionario_superior->id)))) {
                    return abort(404);
                }
            }
        }

        return view('cursos.cuestionario.mostrar', compact('cuestionario', 'curso', 'pagina', 'cuestionario_recuperacion_id'));
    }
    /* 3 Ways - Alexis Bogado */

    protected function getCustionarioRecuperacionId ($cuestionario, $user)
    {
        if ($cuestionario->categoria_id = 3) {
            $cuestionario_recuperacion = $cuestionario->bloque->cuestionarios()->where('categoria_id', 4)->first();

            if (
                $cuestionario_recuperacion
                && is_null($user->getLastCuestionarioById($cuestionario_recuperacion->id))
            ) {
                return $cuestionario_recuperacion->id;
            }
        }

        return null;
    }

    // Enviar formulario del cuestionario
    public function enviarRespuestas ($id, Request $request)
    {
        $pagina = Pagina::getPaginaBySlug('mis_cursos');

        $user = Auth::user();

        $cuestionario = Cuestionario::findOrFail($id);

        $curso = $cuestionario->bloque->curso;
        
        $respuestas = [ "sin_contestar" => 0, "correctas" => 0, "incorrectas" => [ ] ];

        $usuario_cuestionario = $this->nuevoCuestionario($cuestionario->id);

        $oportunidades = $user->cuestionarios->where('cuestionario_id', $cuestionario->id)->count();

        if (($user->tiempo_finalizado($curso) || ($curso->configuracion(1) && !$user->completo_bloque($curso->bloque_anterior($cuestionario->bloque), $cuestionario->bloque))) || ($oportunidades > $cuestionario->oportunidades && $cuestionario->oportunidades > 0))
            return abort(404);

        foreach ($cuestionario->preguntas as $key => $pregunta) {
            $key++;
            
            if (is_null($request->input("respuesta_{$key}")))
                $respuestas["sin_contestar"]++;
            else {

                $pregunta_respuesta = CuestionarioPreguntaRespuesta::where('id', '=', $request->input("respuesta_{$key}"))->first();

                if ($pregunta_respuesta->correcta != 1)
                    $respuestas["incorrectas"][] = $key;
                else
                    $respuestas["correctas"]++;

                $this->storeRespuestas($usuario_cuestionario->id, $request->input("respuesta_{$key}"));
            }
        }
        
        $usuario_cuestionario = $this->actualizarCuestionario($usuario_cuestionario, (((($respuestas["correctas"] * 100) / ($cuestionario->preguntas->count() <= 0 ? 1 : $cuestionario->preguntas->count())) >= $cuestionario->porcentaje) ? "aprobado" : "suspenso"));

        $usuario_curso = $user->usuario_cursos->where('curso_id', $cuestionario->bloque->curso_id)->first();

        DiplomasController::comprobar_diploma($usuario_curso);

        $cuestionario_recuperacion_id = $this->getCustionarioRecuperacionId($cuestionario, $user);

        return view('cursos.cuestionario.resultado', compact('cuestionario', 'usuario_cuestionario', 'respuestas', 'pagina', 'oportunidades', 'cuestionario_recuperacion_id'));
    }

    public function nuevoCuestionario ($cuestionario_id)
    {
        $user = Auth::user();

        $usuario_cuestionario = new UsuarioCuestionario;

        $usuario_cuestionario->cuestionario_id = $cuestionario_id;

        $usuario_cuestionario->usuario_id = $user->id;

        $usuario_cuestionario->save();

        return $usuario_cuestionario;
    }

    // Guardar datos del cuestionario
    public function actualizarCuestionario ($usuario_cuestionario, $estado)
    {
        $usuario_cuestionario->estado = $estado;

        $usuario_cuestionario->save();

        return $usuario_cuestionario;
    }

    // Guardar respuesta en la base de datos
    public function storeRespuestas ($cuestionario_id, $respuesta)
    {
        $cuestionario_respuesta = new UsuarioCuestionarioRespuesta;

        $cuestionario_respuesta->usuarios_cuestionarios_id = $cuestionario_id;

        $cuestionario_respuesta->respuesta_id = $respuesta;

        $cuestionario_respuesta->save();
    }
}
