<?php
namespace Controllers;

use DAO\DueñoDAO;
use DAO\MascotaDAO as MascotaDAO;
use Exception;
use Models\Alert as Alert;
use Models\Mascota as Mascota;
use Models\Archivos as Archivos;

class MascotasController{
    
    private $MascotasDAO;

    public function __construct(){

        $this->MascotasDAO = new MascotaDAO();
    }

    public function VistaMascotas(){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            try{

                $listaMascotas = $this->MascotasDAO->GetAll();

                if($listaMascotas){

                    require_once(VIEWS_PATH. "DashboardDueno/Mascotas.php");

                }else{
                    
                    throw new Exception("No posee ninguna mascota");
                }


            }catch(Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
                
            }


        }else{

            header("location: ../Home");
        }
    }

    public function VerRegistroGato(){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            try{

                $listaRazas = $this->MascotasDAO->listarRazas("Gato");

                if($listaRazas){

                    require_once(VIEWS_PATH. "DashboardDueno/RegistroGato.php");;

                }else{
                    
                    throw new Exception("Error al abrir el registro");
                }

                


            }catch(Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
                
            }

        }else{

            header("location: ../Home");
        }

    }

    public function VerRegistroPerro(){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            try{

                $listaRazas = $this->MascotasDAO->listarRazas("Perro");

                if($listaRazas){

                    require_once(VIEWS_PATH. "DashboardDueno/RegistroPerro.php");

                }else{
                    
                    throw new Exception("Error al abrir el registro");
                }

            }catch(Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
                
            }

        }else{

            header("location: ../Home");
        }

    }

    public function VerFiltroMascotas($alert=null){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            require_once(VIEWS_PATH. "DashboardDueno/FiltroRegistroMascota.php");

        }else{

            header("location: ../Home");
        }
    }

    public function VerPerfilMascota($id){ //CHECKED
        
        
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            try{

                $mascota=$this->MascotasDAO->devolverMascotaPorId($id);

                if($mascota){

                    require_once(VIEWS_PATH."DashboardDueno/perfilMascota.php");

                }else{
                    
                    throw new Exception("Error al cargar el perfil de la mascota");
                }

                

            }catch(Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
                
            }


        }else{

            header("location: ../Home");
        }

    }

    public function AddGato($nombre, $raza, $tamano ,$fotoGato,$fotoPlan, $videoUrl=null){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $MascotasDAO = new MascotaDAO();

            $mascota = new Mascota();
            $mascota->setNombre($nombre);
            $mascota->setRaza($raza);
            $mascota->setEspecie("Perro");
            $mascota->setTamaño($tamano);
            
            $nameImgPerro = $mascota->getNombre() ."-". $fotoGato["name"];
            $nameImgPlan = $mascota->getNombre() ."-". $fotoPlan["name"];

            $mascota->setFotoURL($nameImgPerro);
            $mascota->setPlanVacURL($nameImgPlan);
            $mascota->setVideoURL($videoUrl);

            try{

                if($MascotasDAO->Add($mascota)){

                    Archivos::subirArch("fotoGato", $fotoGato, "Mascotas/FotosMascotas/", $mascota->getNombre());
                    Archivos::subirArch("fotoPlan", $fotoPlan, "Mascotas/PlanesVacunacion/", $mascota->getNombre());
                    header("location:../Mascotas/VistaMascotas");

                }else{

                    throw new Exception("No se pudo agregar la mascota");

                }
                

            }
            catch (Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }

        }else{

            header("location: ../Home");
        }
        
    }

    public function AddPerro($nombre, $raza, $tamano ,$fotoPerro,$fotoPlan, $videoUrl=null){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $MascotasDAO = new MascotaDAO();

            $mascota = new Mascota();
            $mascota->setNombre($nombre);
            $mascota->setRaza($raza);
            $mascota->setEspecie("Perro");
            $mascota->setTamaño($tamano);
            
            $nameImgPerro = $mascota->getNombre() ."-". $fotoPerro["name"];
            $nameImgPlan = $mascota->getNombre() ."-". $fotoPlan["name"];

            $mascota->setFotoURL($nameImgPerro);
            $mascota->setPlanVacURL($nameImgPlan);
            $mascota->setVideoURL($videoUrl);

            try{

                if($MascotasDAO->Add($mascota)){

                    Archivos::subirArch("fotoPerro", $fotoPerro, "Mascotas/FotosMascotas/", $mascota->getNombre());
                    Archivos::subirArch("fotoPlan", $fotoPlan, "Mascotas/PlanesVacunacion/", $mascota->getNombre());
                    header("location:../Mascotas/VistaMascotas");

                }else{

                    throw new Exception("No se pudo agregar la mascota");

                }

            }
            catch (Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }

        }else{

            header("location: ../Home");
        }
    }

    public function removerMascota($id){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            try{

                if($this->MascotasDAO->borrarMascota($id)){

                    header("location: ../Mascotas/VistaMascotas");

                }else{

                    throw new Exception("No se pudo borrar la mascota");

                }

                
            }
            catch (Exception $ex){

                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }
        
        }else{

            header("location: ../Home");
        }
    }


 
    
}