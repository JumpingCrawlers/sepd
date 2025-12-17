<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Mensaje;
use App\Curso;
use App\CursoTutor;
use App\UsuarioCurso;
use App\UsuarioTutor;
use App\Pagina;
use App\Mail\ConsultaTutor;

class MensajesController extends Controller
{
     /** 3Ways - Alex
     * Mostrar vista mensajes (alumno-tutor-curso)
     *
     * @return void
     */
    public function show($curso_id, $usuario_id) {
        $pagina = Pagina::getPaginaBySlug('formacion');

        $id = \Auth::user()->id;        
        $curso = Curso::find($curso_id);
        $usuarios_cursos=UsuarioCurso::where([['usuario_id', '=', $id],['curso_id','=',$curso_id]])->get()->first();
        //Si es un tutor utilizar el model de CursoTutor
        if(!$usuarios_cursos){
           $usuario_tutor=UsuarioTutor::where('usuario_id', '=', $id)->first();
           $usuarios_cursos = CursoTutor::where([['usuario_tutor_id', $usuario_tutor->id],['curso_id', $curso_id]])->get()->first();
        }

        //Obtener solo los mensajes relacionados con el usuario en cuestión
        $mensajes = $usuarios_cursos->mensajesPorUsuario($usuario_id)->get();

        return view('mensajes.browse', compact('curso','mensajes', 'usuario_id', 'pagina'));
    }

    /** 3Ways - Alex
     * Listar todos los mensajes del usuario
     *
     * @return void
     */
    public function list() {
        $pagina = Pagina::getPaginaBySlug('formacion');

        $cantidad_mensajes=0;
        $id = \Auth::user()->id;
        $usuarios_cursos=UsuarioCurso::where('usuario_id', '=', $id)->orderByDesc('id');

        $usuarios_cursos = $usuarios_cursos->get();

        foreach($usuarios_cursos as $key => $curso){
            if($curso->mensajes->count() >= 1) {
                $cantidad_mensajes++;                
            }else{
                $usuarios_cursos->forget($key);
            }
        }
        // Comprobar si es tutor
        if($cantidad_mensajes == 0) {
           $usuario_tutor=UsuarioTutor::where('usuario_id', '=', $id)->first();
           if($usuario_tutor){
               $usuarios_cursos = CursoTutor::where('usuario_tutor_id', $usuario_tutor->id);  

               $usuarios_cursos = $usuarios_cursos->get();
                foreach($usuarios_cursos as $key => $curso){
                    if($curso->mensajes->count() >= 1) {
                        $cantidad_mensajes++;
                    }else{
                        $usuarios_cursos->forget($key);
                    }
                }
            }
        }
        


        if($cantidad_mensajes<1) {
            return view('mensajes.sin-mensajes', compact('pagina'));
        }
        else {
            //Ordenar cursos por fecha de mensaje
            $mensajesOrdenados = Arr::sort($usuarios_cursos, function($usuarios_cursos)
            {
                return $usuarios_cursos->mensajes[0]->created_at;
            });
            $usuarios_cursos = array_reverse($mensajesOrdenados);

            //Foreach para Limitar a solo un enlace por conversación, y no cada respuesta
            foreach($usuarios_cursos as $curso){
                $conversacionesUnicasEmisor = [];
                $conversacionesUnicasReceptor = [];
                foreach($curso->mensajes as $mensaje){
                    //Filtramos los mensajes en los el usuario que es emisor
                    if($mensaje->emisor == $id ){
                        if(in_array($mensaje->receptor, $conversacionesUnicasEmisor) || in_array($mensaje->receptor, $conversacionesUnicasReceptor)){
                            $curso->mensajes = $curso->mensajes->except($mensaje->id);
                        }else{
                            array_push($conversacionesUnicasEmisor, $mensaje->receptor);
                        }                    
                    }
                    //Filtramos los mensajes en los el usuario que es receptor
                    if($mensaje->receptor == $id ){
                        if(in_array($mensaje->emisor, $conversacionesUnicasReceptor) || in_array($mensaje->emisor, $conversacionesUnicasEmisor)){
                            $curso->mensajes = $curso->mensajes->except($mensaje->id);
                        }else{
                            array_push($conversacionesUnicasReceptor, $mensaje->emisor);
                        }                    
                    }    
                }    
            }
        }

        return view('mensajes.list', compact('usuarios_cursos', 'pagina'));
    }

    /** 3Ways - Alex
     * Guardar Mensaje
     *
     * @return void
     */
    public function store( $curso_id, $usuario_id) {
        $id = \Auth::user()->id;
        //Guardar mensaje
        $mensajeNuevo = new Mensaje;
        $mensajeNuevo->emisor = $id;                  
        $mensajeNuevo->receptor = $usuario_id;
        $mensajeNuevo->tema = $curso_id;
        $mensajeNuevo->leido = 0;
        $mensajeNuevo->mensaje = request()->mensaje;  
        $mensajeNuevo->save();

                
        $curso = Curso::find($curso_id);
        $usuarios_cursos=UsuarioCurso::where([['usuario_id', '=', $id],['curso_id','=',$curso_id]])->get()->first();

        //Si es un tutor utilizar el model de CursoTutor
        if(!$usuarios_cursos){
           $usuario_tutor=UsuarioTutor::where('usuario_id', '=', $id)->first();
           $usuarios_cursos = CursoTutor::where('usuario_tutor_id', $usuario_tutor->id)->get()->first();
        }
        //Obtener solo los mensajes relacionados con el usuario en cuestión
        $mensajes = $usuarios_cursos->mensajesPorUsuario($usuario_id)->get();

        // 3 Ways - Alexis Bogado
        // Enviar mail a formacion@sepd con el nombre del curso y la consulta
        Mail::to(setting('site.mail_formacion'))->send(new ConsultaTutor($id, $usuario_id, $curso->titulo, $mensajeNuevo->mensaje));

        return redirect()->action('MensajesController@show', ['id' => $curso_id, 'usuario_id' => $usuario_id]);
    }
    
}
