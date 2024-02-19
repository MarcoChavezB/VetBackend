<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CambioStatus extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $cita;

    /**
     * Create a new message instance.
     */
    public function __construct(Object $user, $cita)
    {
        $this->user = $user;
        $this->cita = $cita;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Actualización de la cita')
            ->view('CambioStatus', ['nombre' => $this->user->nombre, 'estatus' => $this->cita->estatus]);
    }

}
