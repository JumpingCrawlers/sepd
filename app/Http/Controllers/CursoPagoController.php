<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Ssheduardo\Redsys\Facades\Redsys;
use App\Curso;
use App\User;
use App\PromocionClave;
use App\UsuarioPagoCurso;

class CursoPagoController extends InscripcionesController
{
    /* 3 Ways - Alexis Bogado */
    
    /**
     * Mostrar pasarela de pagos
     * 
     * @return View
     */
    public function index($id)
    {
        $user = Auth::user();

        $curso = Curso::findOrFail($id);
        $precio = ($user->es_socio() ? $curso->precio_socio : $curso->precio_nosocio);

        if ($precio <= 0 || $user->usuario_cursos->where('curso_id', $curso->id)->count() > 0 || ($curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria') || ($curso->configuracion(5) && !$user->es_socio()) || $curso->configuracion(4)) return abort(404);
        
        parent::createPago($curso, $precio);
        return self::crear_pasarela($curso, $precio);
    }

    /**
     * Redireccionar según respuesta de tpv
     * 
     * @param int $id Curso id
     * @param string $tipo Tipo de respuesta
     * 
     * @return void
     */
    public function respuestaTipo($id, $tipo)
    {
        $curso = Curso::findOrFail($id);

        if ($tipo == 'ok')
            return redirect()->route('curso.hacer', [ 'id' => $curso->id ])->with([
                'payment-success' => 'El pago se ha realizado correctamente'
            ]);
        else
            return redirect()->route('curso.inscripcion', [ 'id' => $curso->id ])->with([
                'payment-error' => 'No se ha podido realizar el pago, inténtelo de nuevo.'
            ]);
    }

    /**
     * Acciones dependiendo de la respuesta del TPV
     *
     * @param int $id Curso id
     * @param Request $request
     * 
     * @return void
     */
    public function respuesta($id, $uid, Request $request)
    {
        $user = User::find($uid);
        $curso = Curso::findOrFail($id);
        
        // Si no se pasan parametros llevar a vista de error
        if (!$request->input()) goto Error;
        
        if ($user->usuario_cursos->where('curso_id', $curso->id)->count() > 0 || ($curso->descripcion_estado == 'Pendiente de lanzamiento' || $curso->descripcion_estado == 'Cerrado' || $curso->descripcion_estado == 'Próxima convocatoria') || ($curso->configuracion(5) && !$user->es_socio())) return abort(404);

        $parameters = Redsys::getMerchantParameters($request->input('Ds_MerchantParameters'));
        $DsResponse = $parameters["Ds_Response"];
        $DsResponse += 0;

        $usuario_pago = UsuarioPagoCurso::where('usuario_id', $user->id)->join('usuarios_pagos', 'usuarios_pagos_cursos.usuario_pago_id', '=', 'usuarios_pagos.id')->select(['usuarios_pagos_cursos.*', 'usuarios_pagos.*'])->where('usuarios_pagos_cursos.curso_id', $curso->id)->get()->last();

        // Para usar en entorno de prueba, cambiar 'config('redsys.key_cursos')' por 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'
        if (Redsys::check(config('redsys.key_cursos'), $request->input()) && $DsResponse <= 99):
            if ($curso->configuracion(4)): // Acceso por clave promocional, actualizar clave
                $promocion_clave = PromocionClave::find($usuario_pago->clave);
                
                parent::store($curso, $promocion_clave->clave, true, $usuario_pago->id);
                parent::updatePromocionClave($promocion_clave, $user->id);
            else:
                parent::store($curso, $usuario_pago->clave, true, $usuario_pago->id);
            endif;

            return;
            // return redirect()->route('curso.hacer', [ 'id' => $curso->id ])->with([
            //     'payment-success' => 'El pago se ha realizado correctamente'
            // ]);
        endif;

        Error:
        if ($request->input())
            parent::updatePago($usuario_pago->id, true);
        else
            return redirect()->route('curso.inscripcion', [ 'id' => $curso->id ])->with([
                'payment-error' => 'No se ha podido realizar el pago, inténtelo de nuevo.'
            ]);
    }

    /**
     * Crear pasarela de pago
     *
     * @param \App\Curso $curso
     * @param int $precio
     * @param string $promocion_clave
     * 
     * @return void
     */
    public static function crear_pasarela($curso, $precio)
    {
        $user_id = str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT);
        $order_id = "{$user_id}C{$curso->id}";
        $order_id .= self::generateRandomString(12 - strlen($order_id));

        /**
         * Para usar en entorno de prueba
         * 
         * Merchant Code: 999008881
         * Key: sq7HjrUOBfKmC576ILgskD5srU870gJ7
         * 
         * Número de tarjeta: 4548812049400004
         * Caducidad: 12/20
         * CVV: 123
         * CIP: 123456
         */
        try {
            // Datos fijos comunes a todas las peticiones
            Redsys::setCurrency('978');
            Redsys::setTransactiontype('0');
            Redsys::setTerminal('1');
            Redsys::setMethod('T'); //Solo pago con tarjeta, no mostramos iupay
            Redsys::setVersion('HMAC_SHA256_V1');
            Redsys::setLanguage('001');

            // Datos dependientes de configuración y comunes a todas las peticiones
            Redsys::setMerchantcode(config('redsys.merchantcode_cursos'));
            Redsys::setNotification(route('curso.pago.respuesta', [ 'id' => $curso->id, 'uid' => Auth::user()->id ]));
            Redsys::setTradeName(config('redsys.tradename_cursos'));
            Redsys::setEnvironment(config('redsys.environment'));
            Redsys::setAttributesSubmit(
                'btn_submit',
                'btn_submit',
                'Redirigiendo a la página web del banco...',
                'border:0px; background-color:white;', // estilo CSS inlie
                '' // clase CSS
            );

            // Datos de la petición
            Redsys::setOrder($order_id);
            // Las URL de vuelta incluyen el uid para comprobación posterior de la solicitud, 
            //      aunque hay que comprobar si se puede recuperar del response de redsys
            Redsys::setUrlOk(route('curso.pago.respuesta.tipo', [ 'id' => $curso->id, 'tipo' => 'ok' ]));
            Redsys::setUrlKo(route('curso.pago.respuesta.tipo', [ 'id' => $curso->id, 'tipo' => 'ko' ]));
            Redsys::setAmount($precio);
            Redsys::setProductDescription("Compra curso " . $curso->titulo);

            // Redsys::setEnviroment('test'); // Descomentar para habilitar entorno de prueba
    
            // Firma de la transacción
            $key = config('redsys.key_cursos');
            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);

            // Crear el formulario y hacer el submit directo
            $form = Redsys::executeRedirection();

        } catch(Exception $e) {
            echo $e->getMessage();
        }

        return $form;
    }

    /**
     * Generate random string
     *
     * @param int $charCount
     * 
     * @return string
     */
    private static function generateRandomString($charCount)
    {
        if ($charCount <= 0) return null;

        $output = '';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for ($i = 0; $i < $charCount; $i++)
            $output .= $chars[rand(0, (strlen($chars) - 1))];

        return $output;
    }
}
