<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitudSocio extends Mailable
{
    use Queueable, SerializesModels;

    public $datos_solicitud; // datos para el email

    /**
     * Create a new message instance.
     * 
     * @param array datos de la solicitud
     * @return void
     */
    public function __construct($datos_solicitud)
    {
        $this->datos_solicitud = $datos_solicitud;
    }

    /**a<
     * Crear el mensaje utilizando la vista correspondiente.
     *
     * @return $this
     */
    public function build()
    {
        /**
         * 3 ways Euro Fuenmayor - Mejorado asunto de copia al correo configurado con el setting de email.socios, ahora incluye apellidos e ID del usuario receptor del correo
         **/
        return isset($this->datos_solicitud['subject']) ? $this->subject($this->datos_solicitud['subject'])->markdown('emails.socio.solicitud') : $this->markdown('emails.socio.solicitud');
    }
}
