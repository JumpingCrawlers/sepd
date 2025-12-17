<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class SolicitudDatosAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Email data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return Mailable $this
     */
    public function build()
    {
        return $this->subject("Solicitud datos de acceso")->markdown('emails.admin.solicitud-datos', compact('data'));
    }
}
