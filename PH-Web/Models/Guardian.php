<?php 

namespace Models;

use Models\Usuario;

class Guardian extends Usuario{

    
    private $fechaInicio;
    private $fechaFin;
    private $tipoMascota = array();
    private $fotoEspacioURL;
    private $calificacion;
    private $descripcion;
    private $costo;
    
    
    public function setFechaInicio($fechaInicio){

        $this->fechaInicio=$fechaInicio;
    }
    public function getFechaInicio(){

        return $this->fechaInicio;
    }
    public function setFechaFin($fechaFin){

        $this->fechaFin= $fechaFin;
    }
    public function getFechaFin(){

        return $this->fechaFin;
    }
 
    public function getTipoMascota()
    {
        return $this->tipoMascota;
    }

    public function getFotoEspacioURL()
    {
        return $this->fotoEspacioURL;
    }

    public function setFotoEspacioURL($fotoEspacioURL)
    {
        $this->fotoEspacioURL = $fotoEspacioURL;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCosto()
    {
        return $this->costo;
    }

    public function setCosto($costo)
    {
        $this->costo = $costo;
    }

    public function setTipoMascota($tipoMascota)
    {
        $this->tipoMascota = $tipoMascota;
    }

    public function pushTipoMascota($tamaño){

        array_push($this->tipoMascota, $tamaño);
    }
   

    public function getCalificacion()
    {
        return $this->calificacion;
    }


    public function setCalificacion($calificacion)
    {
        $this->calificacion = $calificacion;

    }
}

?>