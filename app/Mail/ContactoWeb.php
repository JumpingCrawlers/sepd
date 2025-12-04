<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactoWeb extends Mailable
{
    use Queueable, SerializesModels;

    public $datos_contacto; // datos para el email

    /**
     * Create a new message instance.
     * 
     * @param array datos del usuario
     * @return void
     */
    public function __construct($datos_contacto)
    {
        $this->datos_contacto = $datos_contacto;
    }

    /**
     * Crear el mensaje utilizando la vista correspondiente.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contacto.mensaje');
    }
}
