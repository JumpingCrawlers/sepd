<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SocioSolicitud extends Model
{
    /* 3 Ways - Alexis Bogado */

    protected $table = 'socios_solicitudes';

    /**
     * Obtener el usuario que ha realizado la solicitud
     * 
     * @return User
     */
    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Obtener el tipo de sociedad
     * 
     * @return SocioTipo
     */
    public function tipo() {
        return $this->belongsTo(SocioTipo::class, 'socio_tipo_id');
    }

    /***************************************************
     *                                                 *
     *  Métodos de control de una solicitud de socio   *
     *                                                 *
     ***************************************************/

    /**
     * Actualizar el mensaje de la solicitud
     * 
     * @return void
     */
    public function pagaSolicitud() {
        $this->mensaje = str_replace(" * Salida inesperada del TPV", "", $this->mensaje); // Quitar de observaciones si existe el mensaje de error
        $this->save();
    }
    
    /**
     * Agregar un mensaje a la solicitud
     * 
     * @return void
     */
    public function errorPagoTPV() {
        $this->mensaje .= " * Salida inesperada del TPV";
        $this->save();
    }

//     public $primaryKey = 'id_solicitud';
//     public $timestamps = false;
    
//     // valores por defecto
//     protected $attributes = [
//         'desestimado' => 0,
//         'envio_postal_nuevo' => 0,
//         'pagado' => 0
//     ];
    
//     /***************************************************
//      * Métodos de control de una solicitud de socio
//      *
//      ***************************************************/

//     // Guardar los string de especialidad y subespecialidad
//     public function guardaEspecialidades($data) {

//         // recorrer la lista de especialidades de la solicitud
//         $separador_especialidad = "";
//         $separador_subespecialidad = "";
//         foreach($data as $campo => $valor) {
//             if (substr($campo,0,10) == "espec_otra") {
//                 if (!empty($valor)) {
//                     $this->especialidad .= $separador_especialidad.$valor;
//                     $separador_especialidad = ". ";
//                 }
//             } elseif (substr($campo,0,6) == "espec_") {
//                 $this->especialidad .= $separador_especialidad.$valor;
//                 $separador_especialidad = ", ";
//             } elseif(substr($campo,0,9) == "subespec_") {
//                 $this->subespecialidad .= $separador_subespecialidad.$valor;;
//                 $separador_subespecialidad = ", ";
//             }
//         }

//     }
//     // Función encriptar de OpenSSL para la SEPD Old
//     function encriptar($data){
//     $key = "keyEncryptS3W";
//     $method = "AES-256-CBC";
//     $secret_iv = 'sepd';
//     $data = trim($data);
    
//     $iv = substr(hash('sha256', $secret_iv), 0, 16);

//     $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
//     $encrypted = base64_encode($encrypted);
        
//     return $encrypted;
// }
//     // Guardar el tipo de socio y la domiciliación
//     public function guardaTipoSocioDomiciliacion($tipo_socio, $data) {

//         // Tipo de socio y Domiciliación
//         switch($tipo_socio) {
//             case "numerario":
//                 $this->tipo_socio = 5;
//                 if ($data['modo_pago']=='tarjeta') {
//                     $this->domiciliacion = '24';
//                     $this->pago_nif = $data['nif_titular_tarjeta'];
//                     $this->visa_titular = $data['titular_tarjeta'];
// //                    $this->visa_num_1 = Crypt::encryptString($data['numero_tarjeta_1']);
// //                    $this->visa_num_2 = Crypt::encryptString($data['numero_tarjeta_2']);
// //                    $this->visa_num_3 = Crypt::encryptString($data['numero_tarjeta_3']);
// //                    $this->visa_num_4 = Crypt::encryptString($data['numero_tarjeta_4']);
// //                    $this->visa_amex = Crypt::encryptString($data['numero_tarjeta_amex']);                    
// //                    $this->visa_mes_caducidad = Crypt::encryptString($data['cad_mes']);
// //                    $this->visa_anno_caducidad = Crypt::encryptString($data['cad_anno']);
//                     $this->tipo_tarjeta = $data['tipo_tarjeta'];
//                     $this->visa_num_1 = $this->encriptar($data['numero_tarjeta_1']);
//                     $this->visa_num_2 = $this->encriptar($data['numero_tarjeta_2']);
//                     $this->visa_num_3 = $this->encriptar($data['numero_tarjeta_3']);
//                     $this->visa_num_4 = $this->encriptar($data['numero_tarjeta_4']);
//                     $this->visa_amex = $this->encriptar($data['numero_tarjeta_amex']);                    
//                     $this->visa_mes_caducidad = $this->encriptar($data['cad_mes']);
//                     $this->visa_anno_caducidad = $this->encriptar($data['cad_anno']);

//                 } else {
//                     $this->domiciliacion = '14';
//                     $this->bank_name = $data['banco'];
// //                    $this->bank_iban_1 = Crypt::encryptString($data['iban']);
// //                    $this->bank_entidad = Crypt::encryptString($data['iban_entidad']);
// //                    $this->bank_oficina = Crypt::encryptString($data['iban_oficina']);
// //                    $this->bank_dc = Crypt::encryptString($data['iban_dc']);
// //                    $this->bank_cuenta = Crypt::encryptString($data['iban_cuenta']);
// //                    $this->bank_iban_6 = Crypt::encryptString($data['iban_cuenta2']);
//                     $this->bank_iban_1 = $this->encriptar($data['iban']);
//                     $this->bank_entidad = $this->encriptar($data['iban_entidad']);
//                     $this->bank_oficina = $this->encriptar($data['iban_oficina']);
//                     $this->bank_dc = $this->encriptar($data['iban_dc']);
//                     $this->bank_cuenta = $this->encriptar($data['iban_cuenta']);
//                     $this->bank_iban_6 = $this->encriptar($data['iban_cuenta2']);
//                     $this->bank_titular = $data['titular_cuenta'];
//                     $this->pago_nif = $data['nif_titular'];
//                 }
//                 $this->centro = $data['centro'];
//                 break;
//             case "formacion":
//                 $this->tipo_socio = 11;
//                 $this->domiciliacion = '20';
//                 $this->pago_nif = null;
//                 $this->centro_MIR = $data['centro'];
//                 break;
//             case "internacional":
//                 $this->tipo_socio = 4;
//                 $this->domiciliacion = '24';
//                 $this->pago_nif = $data['nif_titular_tarjeta'];
//                 if ($data['sociedad'] != 4) {
//                     $this->tipo_socio = $data['sociedad'];
//                 }
// //                $this->visa_titular = $data['titular_tarjeta'];
// //                $this->visa_num_1 = Crypt::encryptString($data['numero_tarjeta_1']);
// //                $this->visa_num_2 = Crypt::encryptString($data['numero_tarjeta_2']);
// //                $this->visa_num_3 = Crypt::encryptString($data['numero_tarjeta_3']);
// //                $this->visa_num_4 = Crypt::encryptString($data['numero_tarjeta_4']);
// //                $this->visa_amex = Crypt::encryptString($data['numero_tarjeta_amex']);
// //                $this->tipo_tarjeta = $data['tipo_tarjeta'];
// //                $this->visa_mes_caducidad = Crypt::encryptString($data['cad_mes']);
// //                $this->visa_anno_caducidad = Crypt::encryptString($data['cad_anno']);
//                 $this->tipo_tarjeta = $data['tipo_tarjeta'];
//                 $this->visa_num_1 = $this->encriptar($data['numero_tarjeta_1']);
//                 $this->visa_num_2 = $this->encriptar($data['numero_tarjeta_2']);
//                 $this->visa_num_3 = $this->encriptar($data['numero_tarjeta_3']);
//                 $this->visa_num_4 = $this->encriptar($data['numero_tarjeta_4']);
//                 $this->visa_amex = $this->encriptar($data['numero_tarjeta_amex']);                    
//                 $this->visa_mes_caducidad = $this->encriptar($data['cad_mes']);
//                 $this->visa_anno_caducidad = $this->encriptar($data['cad_anno']);
//                 break;                
//         }

//     }
    
//     // Preparar el resto de los datos y guardar la solicitud
//     public function guardaSolicitud($data, $codigo_valido) {

//         // preparar el resto de los datos
//     $this->nombre = $data['nombre'];
//         $this->apellidos = $data['apellidos'];
//         $this->via = $data['via'];
//     $this->direccion = $data['direccion'];
//         $this->ciudad = $data['localidad'];
//         $this->cod_postal = $data['cp'];
//         $this->pais = $data['pais'];
//         $this->telefono = $data['telefono'];
//         $this->telefono_movil = $data['telefono_movil'];
//         $this->email = $data['correo'];
//         $this->nacimiento = $data['nacimiento'];
//         $this->dni = $data['nif'];
//         $this->sexo = $data['sexo'];
//         if (isset($data['sociedad'])) {
//             $this->sociedad = $data['sociedad'];
//         }
//         $this->titulacion = $data['titulacion'];
//         if (isset($data['nombre_tutor'])) {
//             $this->nombre_tutor = $data['nombre_tutor'];
//         }
//         if (isset($data['telefono_tutor'])) {
//             $this->telefono_tutor = $data['telefono_tutor'];
//         }
//         if (isset($data['email_tutor'])) {
//             $this->email_tutor = $data['email_tutor'];
//         }
//         if (isset($data['fecha_inicio_mir'])) {
//             $this->fecha_inicio_MIR = $data['fecha_inicio_mir'];
//         }
//         if (isset($data['fecha_fin_mir'])) {
//             $this->fecha_fin_MIR = $data['fecha_fin_mir'];
//         }
//         if (isset($data['publico'])) {
//             $this->publico = $data['publico'];
//         }
// //        $this->departamento = $data[''];
// //        $this->cargo = $data[''];
// //        $this->centro_direccion = $data[''];
// //        $this->centro_localidad = $data[''];
// //        $this->centro_cp = $data[''];
// //        $this->centro_provincia = $data[''];
// //        $this->centro_telefono = $data[''];
//         if (isset($data['publico2'])) {
//             $this->publico2 = $data['publico2'];
//         }
//         if (isset($data['centro2'])) {
//             $this->centro2 = $data['centro2'];
//         }
//         if (isset($data['departamento2'])) {
//             $this->departamento2 = $data['departamento2'];
//         }
//         if (isset($data['cargo2'])) {
//             $this->cargo2 = $data['cargo2'];
//         }
//         if (isset($data['centro_direccion2'])) {
//             $this->centro_direccion2 = $data['centro_direccion2'];
//         }
//         if (isset($data['centro_localidad2'])) {
//             $this->centro_localidad2 = $data['centro_localidad2'];
//         }
//         if (isset($data['centro_cp2'])) {
//             $this->centro_cp2 = $data['centro_cp2'];
//         }
//         if (isset($data['centro_provincia2'])) {
//             $this->centro_provincia2 = $data['centro_provincia2'];
//         }
//         if (isset($data['centro_telefono2'])) {
//             $this->centro_telefono2 = $data['centro_telefono2'];
//         }
// //        $this->publicidad = $data[''];
//         if ($codigo_valido) {
//             $this->observaciones = 'Con clave ' . $codigo_valido->codigo;
//             $this->codigo = $codigo_valido->codigo;
//         }
//         $ahora = \Carbon\Carbon::now();
//         $this->fecha_reg = $ahora->toDateTimeString();
        
//         // guardar el registro
//         $this->save();
//         // actualizar el uid
//         $this->uid = str_pad($this->id_solicitud, 4, "0", STR_PAD_LEFT)
//                 . str_pad($this->tipo_socio, 2, "0", STR_PAD_LEFT)
//                 . $ahora->format('His');
//         $this->save();

//         //Enviar datos bancarios a la SEPD Old para actualizarlos     
//         /*$ch = curl_init();
//         $cadenaPost = "id_solicitud=".$this->id_solicitud."&";
        
//         $cadenaPost = $cadenaPost."visa_num_1=".$this->visa_num_1."&";
//         $cadenaPost = $cadenaPost."visa_num_2=".$this->visa_num_2."&";        
//         $cadenaPost = $cadenaPost."visa_num_3=".$this->visa_num_3."&";
//         $cadenaPost = $cadenaPost."visa_num_4=".$this->visa_num_4."&";
//         $cadenaPost = $cadenaPost."visa_amex=".$this->visa_amex."&";
//         $cadenaPost = $cadenaPost."visa_mes_caducidad=".$this->visa_mes_caducidad."&";
//         $cadenaPost = $cadenaPost."visa_anno_caducidad=".$this->visa_anno_caducidad."&";
        
//         $cadenaPost = $cadenaPost."bank_iban_1=".$this->bank_iban_1."&";
//         $cadenaPost = $cadenaPost."bank_entidad=".$this->bank_entidad."&";
//         $cadenaPost = $cadenaPost."bank_oficina=".$this->bank_oficina."&";
//         $cadenaPost = $cadenaPost."bank_dc=".$this->bank_dc."&";
//         $cadenaPost = $cadenaPost."bank_cuenta=".$this->bank_cuenta."&";
//         $cadenaPost = $cadenaPost."bank_iban_6=".$this->bank_iban_6;
                    
//         curl_setopt($ch, CURLOPT_URL,"https://www0.sepd.es/acceso_administracion/usuarios_update_datos_encriptados.php");
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_POSTFIELDS,$cadenaPost);

//         // In real life you should use something like:
//         // curl_setopt($ch, CURLOPT_POSTFIELDS, 
//         //          http_build_query(array('postvar1' => 'value1')));      

//         $server_output = curl_exec($ch);

//         curl_close ($ch);*/

//     }

//     // Solicitud pagada!
//     public function pagaSolicitud() {

//         // Marcarla como pagada
//         $this->pagado = 1;
//         // Quitar de observaciones si existe el mensaje de error
//     $this->observaciones = str_replace(' * Salida inesperada del TPV', '', $this->observaciones);

//         $this->save();

//     }

//     // Error en el pago por TPV
//     public function errorPagoTPV() {

//         // Añadir error en observaciones
//     $this->observaciones = $this->observaciones.' * Salida inesperada del TPV';

//         $this->save();

//     }

//     /***************************************************
//      * Otras funciones dedicadas, normalmente static
//      *
//      ***************************************************/
    
//     // Proceso de validar registro de usuario
//     // Comprobar si ya existe una solicitud para estos datos
//     public static function checkSolicitud($dni, $email) {

//         return static::where('desestimado', '=', 0)
//                     ->where(function ($query) use ($dni, $email) {
//                         $query->where('dni', 'like', $dni.'%')
//                               ->orWhere('email', '=', $email);
//                     })
//                     ->count() > 0;

//     }

//     // Proceso de hacerse socio
//     // Recuperar la cuota de 
//     public static function getCuotaSolicitudById($id_solicitud) {

//         return static::join('socios__tipos', 'socios__tipos.id_tipo', '=', 'socios__solicitud.tipo_socio')
//                 ->where('id_solicitud', $id_solicitud)
//                 ->pluck('cuota')
//                 ->first();

//     }

}
