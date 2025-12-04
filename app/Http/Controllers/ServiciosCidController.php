<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
// recuperar los datos del usuario conectado
use Auth;
// Encriptar y chequear password
use Hash;
use Illuminate\Support\Facades\Log;

class ServiciosCidController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Servicios CID Controller
    |--------------------------------------------------------------------------
    |
    |

    */
    protected $usuario;
    protected $url = 'https://www1.sepd.es/contenido_privado.php?f=cid_cservicios';


    /**
     * Create a new controller instance.
     * Se recupera el usuario y se controla los datos de especialidad
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('associated');

        // recuperar usuario y datos de especialidad
        // debe hacerse así ya que en __construct no se permite acceder a Auth directamente
        // se crea en una función aparte ya que se debe volver a llamar para refrescar datos de la instancia
        // después de persistir datos en la BD
        $this->middleware(function ($request, $next) {
            $this->init();
            return $next($request);
        });
    }

    protected function init($refresh = false) {
        ($refresh) ? $this->usuario->refresh() : $this->usuario = Auth::user();
    }

    protected function redirigirAntigua() {
        return redirect()->to($this->url);
    }

    protected function redirigirUrl(Request $request) {
        if(isset($request->all()['url_cid'])){
            return redirect()->to(url("/{$request->all()['url_cid']}"));
        }else{
            return redirect()->to($this->url);
        }
    }
    
}