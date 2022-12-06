<?php
namespace Models;

class Mensaje{

    private $fecha;
    private $emisor;
    private $receptor;
    private $contenido;

    public function __construct()
    {
        
    }
    


    public function getFecha()
    {
        return $this->fecha;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

    }
 
    public function getEmisor()
    {
        return $this->emisor;
    }

    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;

    }

    public function getReceptor()
    {
        return $this->receptor;
    }

    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;

    }

    public function getContenido()
    {
        return $this->contenido;
    }
 
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

    }
}
