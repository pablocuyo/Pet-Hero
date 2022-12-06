<?php
namespace Models;

class Review{

    private $id;
    private $fecha;
    private $dueño;//Quien realiza
    private $guardian; //Quien recibe
    private $reserva; //Que reserva se comenta
    private $calificacion; // 1-5
    private $comentario;

    
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function getCalificacion()
    {
        return $this->calificacion;
    }

    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;
    }

    public function getComentario()
    {
        return $this->comentario;
    }

    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }
 
    public function getGuardian()
    {
        return $this->guardian;
    }

    public function setGuardian($guardian)
    {
        $this->guardian= $guardian;

    }

    public function getDueño()
    {
        return $this->dueño;
    }

    public function setDueño($dueño)
    {
        $this->dueño = $dueño;

    }

    public function getReserva()
    {
        return $this->reserva;
    }

    public function setReserva($reserva)
    {
        $this->reserva = $reserva;

    }
}
?>