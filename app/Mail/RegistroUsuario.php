<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistroUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $datos_usuario; // datos para el email

    /**
     * Create a new message instance.
     * 
     * @param array datos del usuario
     * @return void
     */
    public function __construct($datos_usuario)
    {
        $this->datos_usuario = $datos_usuario;
    }

    /**
     * Crear el mensaje utilizando la vista correspondiente.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.usuario.registro');
    }
}
