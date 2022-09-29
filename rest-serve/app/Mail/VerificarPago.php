<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificarPago extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public $session_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $session_id)
    {
        $this->token = $token;
        $this->session_id = $session_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('corporativo@correo.com')
            ->subject('Verificacion de Pago')
            ->view('email', ['token' => $this->token, 'session_id' => $this->session_id]);
    }
}
