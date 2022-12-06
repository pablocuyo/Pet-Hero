<?php   
namespace Models;

use Models\Usuario;

class Dueño extends Usuario{

    private $mascotas = array();
    private $guardianesFav = array();

    public function agregarMascota($mascota){

        array_push($this->mascotas, $mascota);
    }

    public function agregarGuardFav($guardian){
        
        array_push($this->guardianesFav, $guardian);

    }
    public function getGuardianesFav()
    {
        return $this->guardianesFav;
    }
    public function pushGuardianesFav($guardianesFavId)
    {
        array_push($this->guardianesFav, $guardianesFavId);
    }
    public function getMascotas()
    {
        return $this->mascotas;
    }
    public function pushMascotaId($mascotaId)
    {
        array_push($this->mascotas, $mascotaId);
    }
}
    