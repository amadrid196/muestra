<?php

namespace App\Mail\Correo;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contraseñaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $nombre;
    public $usuario;
    public $view;
    public $contraseña;
    public $accion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->nombre  = $data['nombre'];
        $this->usuario = $data['usuario'];
        $this->view = $data["view"];
        $this->contraseña = $data['contraseña'];
        $this->accion = $data['accion'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view)->subject("Cuenta de Usuario");
    }
}
