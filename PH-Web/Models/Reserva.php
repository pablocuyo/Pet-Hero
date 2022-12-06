<?php
namespace Models;

class Reserva{
    private $id;
    private $fecha;
    private $fechaInicio;
    private $fechaFin;
    private $mascota;
    private $guardian;
    private $dueño;
    private $costo;
    private $estado;//Pendiente o Aprobada. Depende de la aceptacion del guardian y pago del cupon


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
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }
    public function getFechaFin()
    {
        return $this->fechaFin;
    }
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }
    public function getMascota()
    {
        return $this->mascota;
    }
    public function setMascota($idMascota)
    {
        $this->mascota = $idMascota;
    }
    public function getGuardian()
    {
        return $this->guardian;
    }
    public function setGuardian($guardian)
    {
        $this->guardian = $guardian;
    }
    public function getDueño()
    {
        return $this->dueño;
    }
    public function setDueño($dueño)
    {
        $this->dueño= $dueño;
    }
    public function getCosto()
    {
        return $this->costo;
    }
    public function setCosto($costo)
    {
        $this->costo = $costo;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;

    }
}



?>