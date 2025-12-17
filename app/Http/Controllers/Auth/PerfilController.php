<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Intervention\Image\Facades\Image;
use Mail;
use Auth; // recuperar los datos del usuario conectado
use Hash; // encriptar y chequear password
use App\Traits\Upload; // trait de subir imagen
use App\UsuarioEspecialidad;
use App\UsuarioInteres;
use App\UsuarioDatoProfesional;
use App\UsuarioCentro;
use App\UsuarioDireccion;
use App\UsuarioDatoBasico;
use App\Curso;
use App\DiplomaCertificado;
use App\UsuarioDiploma;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PerfilController extends Controller
{
    use Upload;

    protected $usuario;

    /**
     * Create a new controller instance.
     * Se recupera el usuario
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // recuperar usuario
        // debe hacerse así ya que en __construct no se permite acceder a Auth directamente
        // se crea en una función aparte ya que se debe volver a llamar para refrescar datos de la instancia
        // después de persistir datos en la BD
        $this->middleware(function ($request, $next) {
            $this->init();

            return $next($request);
        });
    }

    /**
     * Función para refrescar el usuario
     * 
     * @param bool $refresh (optional)
     * 
     * @return void
     */
    protected function init($refresh = false)
    {
        ($refresh) ? $this->usuario->refresh() : $this->usuario = Auth::user();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $valida = [];

        $valida['email'] = 'required|email';
        $valida['tratamiento'] = 'required|string|max:10';
        $valida['nombre'] = 'required|string|max:40';
        $valida['apellidos'] = 'required|string|max:40';
        $valida['password'] = 'nullable|confirmed|min:8|different:old_password'; // cambio de contraseña
        $valida['sexo'] = 'required';
        $valida['via'] = "required|string";
        $valida['direccion'] = "required|string";
        $valida['ciudad'] = "required|string";
        $valida['cod_postal'] = "required|string";

        if (isset($this->usuario->tipo_socio) && $this->usuario->tipo_socio->internacional == 0) $valida['provincia'] = "required";
        if ((isset($this->usuario->tipo_socio) && $this->usuario->tipo_socio->internacional == 1) || $data['provincia'] == '000') $valida['provincia_otros'] = "required|string|nullable";
        if (isset($this->usuario->tipo_socio) && $this->usuario->tipo_socio->internacional == 1) $valida['telefono'] = 'required';
        else $valida['telefono_movil'] = 'required';

        $valida['titulacion'] = 'required|string|max:250';
        // $valida['especialidad'] = "required";
        $valida['cargo'] = 'required';
        $valida['centro'] = 'required';
        $valida['publico'] = 'required';

        return Validator::make($data, $valida);
    }

    /**
     * Mostrar los datos del perfil.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $tab = null)
    {
        $pagina = null;
        
        $miga_pan = '> Mi perfil';

        $nombre_menu = setting('site.menu_principal');
        
        $tipo = $request->has('tipo') ? $request->tipo : 'online';
        
        $user = User::with('cargos.cargo', 'socio.reconocimientos.tipo_reconocimiento')->where('id', Auth::user()->id)->first();

        //Obtener certificados corporativos
        $certificados = DB::table('certificados_corporativos')->where("usuario_id",Auth::user()->id)->get();
        $datos = [];
        
        foreach($certificados as $certificado){
            $data = DB::table('certificados_corporativos_datos')->where("id",$certificado->datos_id)->first();

            if (!in_array($certificado->modelo, [1, 2, 3, 4], true)) {
                continue;
            }

            $id = $certificado->id;

            switch ($certificado->modelo) {

                
                case 1:
                    $nombre_certificado = "Certificado ".$data->tipo_colaboracion. " - ".$data->nombre_curso;
                    $fecha = $certificado->created_at;

                    break;
                case 2:
                    $nombre_certificado = "Certificado Evaluador - ".$data->nombre_congreso;
                    $fecha = $certificado->created_at;

                    break;
                case 3:
                    $nombre_certificado = "Certificado Hands-on - ".$data->nombre_congreso;
                    $fecha = $certificado->created_at;

                    break;
                case 4:
                    $nombre_certificado = "Certificado Autor - ".$data->isbn;
                    $fecha = $certificado->created_at;

                    break;        
                default:
                    $nombre_certificado = 'No encontrado.';
                    $fecha = $certificado->created_at;
                    break;
            }

            $datos[] = ["modelo" => $certificado->modelo, 'nombre_certificado' => $nombre_certificado, 'fecha' => $fecha, 'id' => $id];

            
        }

        if ($tipo == "presencial") {
            $creditos = 0.00;
            $years_check_array = [];
            $usuario_diplomas = $user->diplomas_presenciales();
            foreach ($usuario_diplomas as $key => $usuario_diploma) {
                $year = explode('-', $usuario_diploma->created_at)[0];
                $file_pdf = public_path() . "/storage/diplomas/presenciales/Diploma_{$usuario_diploma->id_evento} {$usuario_diploma->id}.pdf";
                if (!file_exists($file_pdf)) {
                    if (!in_array($year, $years_check_array)) {
                        $creditos += $usuario_diploma->creditos;
                        array_push($years_check_array, $year);
                    } else {
                        if ($year < 2018) {
                            $usuario_diplomas->forget($key);
                        }
                    }
                } else {
                    $creditos += $usuario_diploma->creditos;
                }
            }
        } else {
            // Obtener historial acreditaciones

            $usuario_diplomas = $user->usuario_diplomas_obtiene_diploma()->paginate(12);

            $cursos_creditos = [];

            $creditos = 0.00;

            foreach ($user->usuario_diplomas_obtiene_diploma() as $usuario_diploma) {
                if (in_array($usuario_diploma->curso_id, $cursos_creditos)) {
                    continue;
                }

                array_push($cursos_creditos, $usuario_diploma->curso_id);
                $creditos += $usuario_diploma->curso->creditos;
            }
        }
        // si tiene un diploma certificado
        $certificados = DiplomaCertificado::where("alumno_id",$user->id)->get();

        $certificado_curso = [];

        foreach($certificados as $cert){
            $certificado_curso[$cert->curso_id] = Curso::select("titulo")->where("id",$cert->curso_id)->first();
        }
        return view('perfil.show', [
            'pagina' => $pagina,
            'nombre_menu' => $nombre_menu,
            'miga_pan' => $miga_pan,
            'usuario' => $this->usuario,
            'usuario_diplomas' => $usuario_diplomas,
            'creditos' => $creditos,
            'user' => $user,
            'tab' => $tab,
            'certificados' => $certificados,
            'certificado_curso' => $certificado_curso,
            'certificados_corporativos' => $datos,
            
        ]);
    }

    /**
     * Mostrar el formulario con los datos de perfil editables.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $pagina = null;
        $miga_pan = '> Mi perfil';
        $nombre_menu = setting('site.menu_principal');

        return view('perfil.edit', [
            'pagina' => $pagina,
            'nombre_menu' => $nombre_menu,
            'miga_pan' => $miga_pan,
            'usuario' => $this->usuario
        ]);
    }

    /**
     * Validar y lanzar la actualización de los datos de perfil.
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        $validator->validate();

        if (isset($request->password) && !Hash::check($request->old_password, $this->usuario->password)) : // el cambio de contraseña solo si coincide con el anterior
            $validator->after(function ($validator) {
                $validator->errors()->add('old_password', 'La contraseña introducida no coincide con la que tenemos almacenada!');
            })->validate();
        endif;

        $this->actualizaPerfilUsuario($request->all());
        $this->actualizarEspecialidades($request->all());
        $this->actualizarIntereses($request->all());
        $this->actualizaPerfilSocio($request->all());

        if ($file = $request->file('imagen')) : // si hay imagen de perfil subir a la carpeta de perfiles con los tres tamaños que se utilizan actualmente
            $this->upload($file, "/perfil/{$this->usuario->id}/{$this->usuario->id}.jpg");
            $this->upload($file, "/perfil/{$this->usuario->id}/ico_{$this->usuario->id}.jpg", 100);
            $this->upload($file, "/perfil/{$this->usuario->id}/medio_{$this->usuario->id}.jpg", 63);
            $this->upload($file, "/perfil/{$this->usuario->id}/mini_{$this->usuario->id}.jpg", 50);
            // FIXED DE LA FOTO DE PERFIL 
            $this->copyProfile();
        endif;

        $this->init(true); // refresh $usuario

        session()->flash('mensaje_flash', (getHtmlIconoFlash('ok') . ' Tus datos se han guardado correctamente!')); // Mensaje de actualización correcta

        if ($request->previousRoute) return redirect("/{$request->previousRoute}");

        return redirect()->route('perfil');
    }

    /**
     * Actualizar los intereses del usuario
     * 
     * @param array $data (Request)
     * 
     * @return void
     */
    protected function actualizarIntereses(array $data)
    {
        $areas_ids = $data["area_inter"];

        $this->usuario->area_intereses()->sync($areas_ids);

        // $intereses = isset($data["area_inter"]) ? $data["area_inter"] : null;
        // if (!$intereses || !is_array($intereses)) return;

        // // Borrar los que tiene actualmente y se han quitado en el front
        // $usuarios_intereses_ids = array_map(function ($usuario_interes) {
        //     return (string) $usuario_interes["interes_id"];
        // }, $this->usuario->intereses->toArray());

        // foreach ($usuarios_intereses_ids as $usuario_interes_id) :
        //     if (in_array($usuario_interes_id, $intereses)) continue;

        //     $usuario_interes = UsuarioInteres::where(['usuario_id' => $this->usuario->id], ['interes_id' => $usuario_interes_id])->first();
        //     $usuario_interes->delete();
        // endforeach;

        // // Insertar nuevos intereses que no tenía
        // $usuarios_intereses = [];

        // foreach ($intereses as $interes) :
        //     if ($this->usuario->hasInteres($interes)) continue;

        //     $usuarios_intereses[] = new UsuarioInteres([
        //         'usuario_id' => $this->usuario->id,
        //         'interes_id' => $interes
        //     ]);
        // endforeach;

        // $this->usuario->intereses()->saveMany($usuarios_intereses);
    }

    /**
     * Actualizar las especialidades del usuario
     * 
     * @param array $data (Request)
     * 
     * @return void
     */
    protected function actualizarEspecialidades(array $data)
    {
        $especialidades = isset($data["especialidad"]) ? $data["especialidad"] : null;
        if (!$especialidades || !is_array($especialidades)) return;

        // Borrar los que tiene actualmente y se han quitado en el front
        $usuarios_especialidades_ids = array_map(function ($usuario_especialidad) {
            return (string) $usuario_especialidad["especialidad_id"];
        }, $this->usuario->especialidades->toArray());

        foreach ($usuarios_especialidades_ids as $usuario_especialidad_id) :
            if (in_array($usuario_especialidad_id, $especialidades)) continue;

            $usuario_especialidad = UsuarioEspecialidad::where(['usuario_id' => $this->usuario->id], ['especialidad_id' => $usuario_especialidad_id])->first();
            if ($usuario_especialidad && $usuario_especialidad->especialidad->habilitado == 1) $usuario_especialidad->delete();
        endforeach;

        // Insertar nuevas especialidades que no tenía
        $usuarios_especialidades = [];

        foreach ($especialidades as $especialidad) :
            if ($this->usuario->hasEspecialidad($especialidad)) continue;

            $usuarios_especialidades[] = new UsuarioEspecialidad([
                'usuario_id' => $this->usuario->id,
                'especialidad_id' => $especialidad
            ]);
        endforeach;

        $this->usuario->especialidades()->saveMany($usuarios_especialidades);
    }

    /**
     * Guardar los datos de usuario
     *
     * @param array $data (Request)
     * 
     * @return void
     */
    protected function actualizaPerfilUsuario(array $data)
    {
        if (!empty($data['password'])) : // Si hay algo en el password, modificar la contraseña
            $this->usuario->password = Hash::make($data['password']);
        endif;

        // Crear datos básicos si el usuario no tiene
        if (!$this->usuario->datos_basicos) :
            $datos_basicos = new UsuarioDatoBasico;
            $datos_basicos->usuario_id = $this->usuario->id;
            $datos_basicos->save();

            $this->usuario->setRelation('datos_basicos', $datos_basicos);
        endif;

        // Crear dirección si el usuario no tiene
        if (!$this->usuario->direcciones) :
            $direccion = new UsuarioDireccion;
            $direccion->usuario_id = $this->usuario->id;
            $direccion->save();

            $this->usuario->setRelation('direcciones', $direccion);
        endif;

        $this->checkCambio($this->usuario, $data, 'email') ? $this->usuario->email = $data['email'] : null;
        $this->checkCambio($this->usuario, $data, 'tratamiento') ? $this->usuario->tratamiento = $data['tratamiento'] : null;
        $this->checkCambio($this->usuario, $data, 'nombre') ? $this->usuario->nombre = $data['nombre'] : null;
        $this->checkCambio($this->usuario, $data, 'apellidos') ? $this->usuario->apellidos = $data['apellidos'] : null;

        $this->checkCambio($this->usuario->datos_basicos, $data, 'telefono') ? $this->usuario->datos_basicos->telefono = $data['telefono'] : null;
        $this->checkCambio($this->usuario->datos_basicos, $data, 'telefono_movil') ? $this->usuario->datos_basicos->movil = $data['telefono_movil'] : null;
        $this->usuario->datos_basicos->sexo = $data['sexo'];
        $this->usuario->datos_basicos->fecha_nacimiento = $data['nacimiento'];

        $this->checkCambio($this->usuario->direcciones, $data, 'via') ? $this->usuario->direcciones->via = $data['via'] : null;
        $this->checkCambio($this->usuario->direcciones, $data, 'direccion') ? $this->usuario->direcciones->direccion = $data['direccion'] : null;
        $this->checkCambio($this->usuario->direcciones, $data, 'ciudad') ? $this->usuario->direcciones->ciudad = $data['ciudad'] : null;
        $this->checkCambio($this->usuario->direcciones, $data, 'cod_postal') ? $this->usuario->direcciones->codigo_postal = $data['cod_postal'] : null;
        $this->checkCambio($this->usuario->direcciones, $data, 'pais') ? $this->usuario->direcciones->pais_id = $data['pais'] : null;
        $this->usuario->guardaProvincia($data);

        $this->usuario->save();
        $this->usuario->datos_basicos->save();
        $this->usuario->direcciones->save();
    }

    /**
     * Guardar los datos de socio
     *
     * @param array $data (request)
     * 
     * @return void
     */
    protected function actualizaPerfilSocio(array $data)
    {
        // Crear datos profesionales si el usuario no tiene
        if (!$this->usuario->datos_profesionales) :
            $datos_profesionales = new UsuarioDatoProfesional;
            $datos_profesionales->usuario_id = $this->usuario->id;
            $datos_profesionales->save();

            $this->usuario->setRelation('datos_profesionales', $datos_profesionales);
        endif;

        // Crear centro si el usuario no tiene
        if (!$this->usuario->centros) :
            $centro = new UsuarioCentro;
            $centro->usuario_id = $this->usuario->id;
            $centro->save();

            $this->usuario->setRelation('centros', $centro);
        endif;

        $this->checkCambio($this->usuario->datos_profesionales, $data, 'titulacion') ? $this->usuario->datos_profesionales->titulacion = $data['titulacion'] : null;
        $this->checkCambio($this->usuario->datos_profesionales, $data, 'residencia') ? $this->usuario->datos_profesionales->residencia = $data['residencia'] : null;
        $this->checkCambio($this->usuario->datos_profesionales, $data, 'fecha_inicio_MIR') ? $this->usuario->datos_profesionales->fecha_inicio_MIR = $data['fecha_inicio_MIR'] : null;
        $this->checkCambio($this->usuario->datos_profesionales, $data, 'fecha_fin_MIR') ? $this->usuario->datos_profesionales->fecha_fin_MIR = $data['fecha_fin_MIR'] : null;
        $this->checkCambio($this->usuario->centros, $data, 'centro') ? $this->usuario->centros->centro = $data['centro'] : null;
        $this->checkCambio($this->usuario->centros, $data, 'cargo') ? $this->usuario->centros->cargo = $data['cargo'] : null;
        $this->checkCambio($this->usuario->centros, $data, 'publico') ? $this->usuario->centros->publico = $data['publico'] : null;
        $this->checkCambio($this->usuario->centros, $data, 'centro_direccion') ? $this->usuario->centros->direccion = $data['centro_direccion'] : null;

        $this->usuario->datos_profesionales->save();
        $this->usuario->centros->save();
    }

    /**
     * Controlar si hay cambio de valor
     *
     * @param stdClass $objeto
     * @param array $data (Request)
     * @param object $key (campo)
     * 
     * @return void
     */
    protected function checkCambio($objeto, $data, $key)
    {
        return (strtolower($objeto->$key) != strtolower($data[$key]));
    }

    /**
     * ESTO NO DEBE SER, PERO COMO ESTÁ CONFIGURADO EL SERVIDOR FUE LA ÚNICA SOLUCIÓN QUE SE ME PRESENTÓ, YA QUE NO TENGO ACCESO SSH PARA LANZAR EL STORAGE:LINK DE LARAVEL
     *
     * @return void
     */
    protected function copyProfile ()
    {
        try {
            $disk = config('voyager.storage.disk');
            foreach ([
                "{$this->usuario->id}/{$this->usuario->id}.jpg",
                "{$this->usuario->id}/ico_{$this->usuario->id}.jpg",
                "{$this->usuario->id}/medio_{$this->usuario->id}.jpg",
                "{$this->usuario->id}/mini_{$this->usuario->id}.jpg"
            ] as $key => $filename) {
                if (!\Storage::disk($disk)->exists("/storage/perfil/{$filename}"))  {
                    $fileContents = \Storage::disk($disk)->get("/perfil/{$filename}");
                    \Storage::disk('public_directory')->put("/storage/perfil/{$filename}", $fileContents);
                }
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function descargarCertificado($id)
    {
        
        $certificado = DB::table('certificados_corporativos')->where("id",$id)->first();

        
        $datos = DB::table('certificados_corporativos_datos')->where("id",$certificado->datos_id)->first();

        $usuario_diploma = UsuarioDiploma::where("usuario_id",$certificado->usuario_id)->where("curso_id",$certificado->curso_id)->first();
        
                if($usuario_diploma){
                    $code = $usuario_diploma->id;
                }else{
                    $codigo = DB::table("certificados_corporativos_code")->where("certificado_id",$id)->first();

                    if($codigo){
                        $code = $codigo->code;
                    }else{
                        $code = null;
                    }
                }

        
                
        $vista = match($certificado->modelo) {
            1 => 'certificados-corporativos.formacion',
            2 => 'certificados-corporativos.evaluador',
            3 => 'certificados-corporativos.hands-on',
            4 => 'certificados-corporativos.autor-publicacion',
            default => abort(404, 'Modelo de certificado no encontrado'),
        };
        
        
        
        $data = (array) $datos;

        $data["usuario_diploma"] = $code;
        $data["fecha"] = Carbon::parse($data['fecha'])->locale('es')->translatedFormat('j \d\e F \d\e Y');
        $nombre_certificado2 = "";

        switch($certificado->modelo){
            case 1: 
                $nombre_certificado2 = str_replace(' ','_',$data['nombre_curso']);
                $nombre_certificado = $data["tipo_colaboracion"]."_".$nombre_certificado2;
            break;
            case 2:
                $nombre_certificado2 = str_replace(' ','_',$data['nombre_congreso']); 
                $nombre_certificado = "Evaluador_".$nombre_certificado2;
            break;
            case 3:
                $nombre_certificado2 = str_replace(' ','_',$data['nombre_congreso']); 
                $nombre_certificado = "Hands-on_".$nombre_certificado2;
            break;
            case 4:
                $nombre_certificado2 = str_replace(' ','_',$data['isbn']); 
                $nombre_certificado = "Autor_".$nombre_certificado2;
            break;
            default: $nombre_certificado = $id;
            break;
        }
        
        $pdf = Pdf::loadView($vista, $data)->setPaper('a4', 'portrait')->setOptions(['isHtml5ParserEnabled' >= true, 'isRemoteEnabled' => true,'defaultFont' => 'Archivo','chroot' => realpath(public_path())]);

        $dompdf = $pdf->getDomPDF();

        $dompdf->setHttpContext(stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ],
        ]));

        $dompdf->render();
        
        return $pdf->download("Certificado_{$nombre_certificado}.pdf");

        
    }
}
