<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class SolicitudDatos extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User name
     *
     * @var string
     */
    public $userName;

    /**
     * Create a new message instance.
     *
     * @param string $userName
     */
    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return Mailable $this
     */
    public function build()
    {
        return $this->subject("Solicitud datos de acceso")->markdown('emails.socio.solicitud-datos', [ 'user_name' => $this->userName ]);
    }
}
