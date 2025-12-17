<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoVpcr;
use App\UsuarioVpcr;

class VpcrController extends Controller
{
    /* 3 Ways - Alexis Bogado */

    public function sendMail($id, $user_id)
    {
        $usuario_vpcr = UsuarioVpcr::findOrFail($this->decrypt($id));
        if (!$usuario_vpcr || $usuario_vpcr->usuarios_id != $this->decrypt($user_id) || $usuario_vpcr->fecha_enviado) return [ 'success' => false ];

        Mail::to(setting('site.mail_vpcr'))->queue(new NuevoVpcr([
            'nombre' => $usuario_vpcr->usuario->nombre,
            'apellidos' => $usuario_vpcr->usuario->apellidos,
            'email' => $usuario_vpcr->usuario->email,
            'vpcr_id' => $usuario_vpcr->id        
        ]));
    }

    
    /**
     * Función para desencriptar
     * 
     * @param string $data
     * 
     * @return string
     */
    private function decrypt($data)
    {
        $key = "keyEncryptS3W";
        $method = "AES-256-CBC";
        $secret_iv = 'sepd';
        $_data = trim($data);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $_data = openssl_decrypt(base64_decode($_data), $method, $key, 0, $iv);
        
        return $_data;
    }
}
