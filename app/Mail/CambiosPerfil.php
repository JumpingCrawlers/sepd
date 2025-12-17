<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CambiosPerfil extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $datos_cambios; // datos para el email

    /**
     * Create a new message instance.
     * 
     * @param array datos del usuario
     * @return void
     */
    public function __construct($usuario, $datos_cambios)
    {
        $this->usuario = $usuario;
        $this->datos_cambios = $datos_cambios;
    }

    /**
     * Crear el mensaje utilizando la vista correspondiente.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.perfil.cambios');
    }
}
