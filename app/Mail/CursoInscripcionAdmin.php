<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CursoInscripcionAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $curso;
    public $factura;

    /**
     * Create a new message instance.
     *
     * @param User $user Usuario
     * @param Curso $curso Título del curso
     * @param Factura $factura Factura generada
     * 
     * @return void
     */
    public function __construct($user, $curso, $factura)
    {
        $this->user = $user;
        $this->curso = $curso;
        $this->factura = $factura;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $bill_code = 'C' . substr($this->factura->numero, 2, 2) . '-' . substr($this->factura->numero, 4, 4);

        $factura = PDF::loadView('base.factura', [
            'factura' => $this->factura,
            'code' => $bill_code
        ])->setOptions(['defaultFont' => 'sans-serif']);
        
        return $this->subject("Inscripción en curso de la plataforma de E-Learning de la SEPD")
                    ->attachData($factura->output(), "{$bill_code}.pdf")
                    ->markdown('emails.admin.curso-inscripcion');
    }
}
