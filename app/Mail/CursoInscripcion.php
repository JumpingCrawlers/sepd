<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
class CursoInscripcion extends Mailable
{
    use Queueable, SerializesModels;

    public $usuarioCurso;
    public $factura;
    public $usuario;
    public $curso_titulo;

    /**
     * Create a new message instance.
     *
     * @param UsuarioCurso $usuarioCurso Título del curso
     * @param Factura $factura Factura generada
     * 
     * @return void
     */
    public function __construct($usuario_curso, $factura)
    {
        $this->usuarioCurso = $usuario_curso;
        $this->factura = $factura;
        $this->usuario = $usuario_curso->usuario;
        $this->curso_titulo = $usuario_curso->curso->titulo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!$this->factura) {
            return $this->subject("Inscripción en curso de la plataforma de E-Learning de la SEPD")
                ->markdown(($this->usuarioCurso->usuario->es_socio() ? 'emails.curso.inscripcion-socios' : 'emails.curso.inscripcion-nosocios'));
        }

        $bill_code = 'C' . substr($this->factura->numero, 2, 2) . '-' . substr($this->factura->numero, 4, 4);
        
        $factura = PDF::loadView('base.factura', [
            'factura' => $this->factura,
            'code' => $bill_code
        ])->setOptions(['defaultFont' => 'sans-serif']);

        return $this->subject("Inscripción en curso de la plataforma de E-Learning de la SEPD")
            ->attachData($factura->output(), "{$bill_code}.pdf")
            ->markdown(($this->usuarioCurso->usuario->es_socio() ? 'emails.curso.inscripcion-socios' : 'emails.curso.inscripcion-nosocios'));
    }
}
