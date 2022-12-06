<?php 

namespace Models;

use Models\Reserva as Reserva;

class Mail{

    private $header; 

    public function __construct()
    {
        $header = "From: petherolab@gmail.com" . "\r\n";
        $header .= "Reply-To: petherolab@gmail.com" . "\r\n";
        $header .= "X-Mailer: PHP/" . phpversion();
    }

    public function enviarMail(Reserva $reserva){

        
        
        $email = $reserva->getDueño()->getCorreoelectronico();

        $asunto = "Confirmacion de reserva del guardian " . $reserva->getGuardian()->getUsername();

        $mensaje = "Su reserva con el guardian " . $reserva->getGuardian()->getUsername() . " a sido aceptada para el dia ". $reserva->getFechaInicio() . " con finalizacion en " . $reserva->getFechaFin() . ". La mascota a cuidar es " . $reserva->getMascota()->getNombre() . " con un importe de $" . $reserva->getCosto() . ". El importe a pagar en primera estancia es el 50%";

        mail($email, $asunto, $mensaje, $this->header);

        
    }

}

?>