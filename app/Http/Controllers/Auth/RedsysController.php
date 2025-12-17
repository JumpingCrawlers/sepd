<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Ssheduardo\Redsys\Facades\Redsys;
use App\SocioSolicitud;
use App\Pagina;
use App\UsuarioPago;
use App\UsuarioPagoSocio;

class RedsysController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    /**
     * Generar pasarela de pago con los datos de la solicitud
     * 
     * @param Request $request Datos de la solicitud
     * 
     * @return string|null
     */
    public function index(Request $request) {
        $solicitud = SocioSolicitud::findOrFail($request->uid);
        
        $solicitud_id = str_pad($solicitud->id, 4, '0', STR_PAD_LEFT);
        $order_id = "{$solicitud_id}_";
        $order_id .= self::generateRandomString(12 - strlen($order_id));

        $orderId = rand(1000, 9999) . strtoupper(str_limit(md5(time() . str_random(8) . $solicitud->id), 8, ""));

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
            Redsys::setMethod('T'); // Solo pago con tarjeta, no mostramos iupay
            Redsys::setVersion('HMAC_SHA256_V1');
            Redsys::setLanguage('001');

            // Datos dependientes de configuración y comunes a todas las peticiones
            Redsys::setMerchantcode(config('redsys.merchantcode'));
            Redsys::setNotification(route('redsys_notification', [ 'uid' => $this->encrypt($solicitud->id) ]));
            Redsys::setTradeName(config('redsys.tradename'));
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
            Redsys::setUrlOk(route('tpv_respuesta', [ 'result' => 'OK', 'uid' => $this->encrypt($solicitud->id) ]));
            Redsys::setUrlKo(route('tpv_respuesta', [ 'result' => 'KO', 'uid' => $this->encrypt($solicitud->id) ]));
            Redsys::setAmount($solicitud->tipo->cuota);
            Redsys::setProductDescription($request->descripcion);

            // Redsys::setEnviroment('test'); // Descomentar para habilitar entorno de prueba
    
            // Firma de la transacción
            $key = config('redsys.key');
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
     * Control de la vuelta de la transacción TPV
     * 
     * @param Request $request Datos del formulario 
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function confirm($uid, Request $request) {
        $solicitud = SocioSolicitud::findOrFail($this->decrypt($uid));
        
        if (!$request->input("Ds_MerchantParameters")) goto Error;
        
        $parameters = Redsys::getMerchantParameters($request->input("Ds_MerchantParameters"));
        $Ds_response = $parameters["Ds_Response"];
        $Ds_response += 0;
        
        // Para usar en entorno de prueba, cambiar 'config('redsys.key')' por 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'
        if (Redsys::check(config('redsys.key'), $request->input()) && $Ds_response <= 99):
            $solicitud->pagaSolicitud();
            
            $usuario_pago_id = $this->storeUsuarioPago($solicitud, true);
            $this->storeUsuarioPagoSocio($usuario_pago_id, $solicitud);

            return;
        endif;
        
        Error:
        $solicitud->errorPagoTPV();

        $usuario_pago_id = $this->storeUsuarioPago($solicitud, false);
        $this->storeUsuarioPagoSocio($usuario_pago_id, $solicitud);
    }

    /**
     * 
     */
    public function check(Request $request) {
        $pagina = Pagina::getPaginaBySlug('hazte_socio');
        if (!$request->uid) goto Error;

        $solicitud = SocioSolicitud::find($this->decrypt($request->uid));
        if ($solicitud && $request->result == "OK")
            return view('socios.confirmacion', [
                'pagina' => $pagina,
                'tpv' => 0,
                'finalizado' => true
            ]);

        Error:
        return view('socios.confirmacion', [
            'pagina' => $pagina,
            'tpv' => 1,
            'uid' => ($solicitud ? ($solicitud->id + 1) : 0),
            'retry' => ($solicitud ? true : false)
        ]);
    }

    /**
     * Guardar el pago en la base de datos
     * 
     * @param SocioSolicitud $solicitud Solicitud del usuario
     * @param bool $cobrado
     * 
     * @return int
     */
    private function storeUsuarioPago($solicitud, $cobrado) {
        $usuario_pago = new UsuarioPago;
        $usuario_pago->usuario_id = $solicitud->usuario->id;
        $usuario_pago->estado_pago_id = ($cobrado ? 4 : 1); // Pago estado: cobrado
        $usuario_pago->metodo_pago_id = 24; // Pago método: tarjeta
        $usuario_pago->precio = $solicitud->tipo->cuota;

        $usuario_pago->save();

        return $usuario_pago->id;
    }

    /**
     * Guardar el pago de tipo socio en la base de datos
     * 
     * @param int $usuario_pago_id Id del usuario pago
     * @param SocioSolicitud Datos de la solicitud
     * 
     * @return void
     */
    private function storeUsuarioPagoSocio($usuario_pago_id, $solicitud) {
        $usuario_pago_socio = new UsuarioPagoSocio;
        $usuario_pago_socio->usuario_pago_id = $usuario_pago_id;
        $usuario_pago_socio->socio_solicitud_id = $solicitud->id;
        $usuario_pago_socio->sociedad_id = $solicitud->tipo->sociedad_id;
        $usuario_pago_socio->socio_tipo_id = $solicitud->tipo->id;
        $usuario_pago_socio->fecha_estado = \Carbon\Carbon::now();
        $usuario_pago_socio->cuota_year = date('Y');
        $usuario_pago_socio->actual = 1;

        $usuario_pago_socio->save();
    }

    /**
     * Función para encriptar
     * 
     * @param string $data
     * 
     * @return string
     */
    private function encrypt($data) {
        $key = "keyEncryptS3W";
        $method = "AES-256-CBC";
        $secret_iv = 'sepd';
        $data = trim($data);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
        $encrypted = base64_encode($encrypted);

        return $encrypted;
    }

    /**
     * Función para desencriptar
     * 
     * @param string $data
     * 
     * @return string
     */
    function decrypt($data) {
        $key = "keyEncryptS3W";
        $method = "AES-256-CBC";
        $secret_iv = 'sepd';
        $_data = trim($data);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $_data = openssl_decrypt(base64_decode($_data), $method, $key, 0, $iv);
        
        return $_data;
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
