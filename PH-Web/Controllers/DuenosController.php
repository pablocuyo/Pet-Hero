<?php

namespace Controllers;

use DAO\DueñoDAO as DueñoDAO;
use DAO\GuardianDAO;
use DAO\MascotaDAO;
use DAO\ReservaDAO;
use DAO\ReviewDAO;
use Models\Dueño as Dueño;
use DAO\UserDAO as UserDAO;
use Exception;
use Models\Archivos;
use Models\Alert;
use Models\Review;

class DuenosController
{

    private $DueñoDAO;
    private $UserDAO;

    public function __construct()
    {

        $this->DueñoDAO = new DueñoDAO();
        $this->UserDAO = new UserDAO();
    }

    /* FUNCIONES DE REGISTRO */

    public function VistaRegistro($alert = null) //CHECKED
    {
        require_once(VIEWS_PATH . "RegistroDueño.php");
    }

    public function Add($username, $nombre, $apellido, $dni, $mail, $telefono, $direccion, $password, $rePassword, $fotoPerfil) //CHECKED
    {

        
        $dueño = new Dueño();
        $dueño->setUsername($username);
        $dueño->setDni($dni);
        $dueño->setNombre($nombre);
        $dueño->setApellido($apellido);
        $dueño->setCorreoelectronico($mail);
        $dueño->setTelefono($telefono);
        $dueño->setDireccion($direccion);

        $nameImg = $dueño->getUsername() ."-". $fotoPerfil["name"];

        $dueño->setFotoPerfil($nameImg);

        
        try{

            if(!$this->UserDAO->checkUsuario($username,$dni, $mail)){ 

                if($password == $rePassword){
    
                    $dueño->setPassword($password);

                    if($this->UserDAO->addDueño($dueño)){

                        Archivos::subirArch("fotoPerfil", $fotoPerfil, "FotosUsuarios/", $dueño->getUsername());

                        $this->DueñoDAO->Add($dueño);
                        
                        header("location: ../Home?alert=Perfil creado con exito. Puede loguearse&tipo=success");

                    }else{

                        throw new Exception("Error de servidor, intente nuevamente"); 
                    }

                    
    
                }else{

                    throw new Exception("Las contraseñas no coinciden"); 
                }
                
                
            
            }else{

                throw new Exception("El usuario ya existe");

            }
            

        }catch (Exception $ex){
            
            $this->VistaRegistro($ex->getMessage());
    
        }   
            
    }

    /* FUNCIONES VARIAS */

    public function VistaEditarPerfil() //CHECKED
    {

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            try{

                $usuario = $this->DueñoDAO->devolverDueñoPorId($_SESSION["UserId"]);

                if($usuario){
    
                    require_once(VIEWS_PATH . "/DashboardDueno/EditarPerfil.php");
                    
                }else{

                    throw new Exception("Error al intentar editar el pefil");
                }
    
            
            }catch(Exception $ex){
    
                header("location: ../Home?alert=" .$ex->getMessage()."&tipo=danger");
            }        

        }else{

            header("location: ../Home");
        }
        
        
    }

    public function VistaDashboard($alert=null, $tipo=null) //CHECKED
    {
        
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            require_once(VIEWS_PATH . "DashboardDueno/Dashboard.php");

        }else{

            header("location: ../Home");
        }
    }
    
    public function VerMensajes(){ //CHECKED
        
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            require_once(VIEWS_PATH. "/DashboardDueno/VerMensajes.php");

        }else{

            header("location: ../Home");
        }
        
    }

    /* PARTE QUE INTERACTUA CON LOS GUARDIANES */

    public function VistaGuardianes($alert=null, $fechaMin=null, $fechaMax=null, $nombreGuardian=null) //CHECKED
    {
            
        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            $guardianDAO = new GuardianDAO();

            $listaGuardianes = array();
            $resultBuscado = null;
            
            try{

                if($_POST){

                    $resultBuscado= $this->FiltrarGuardianes($fechaMin, $fechaMax, $nombreGuardian);
                }

                if($resultBuscado){

                    $listaGuardianes = $resultBuscado;
                    require_once(VIEWS_PATH . "DashboardDueno/Guardianes.php");
                        

                }else{

                    $listaGuardianes = $guardianDAO->GetAll();
                    require_once(VIEWS_PATH . "DashboardDueno/Guardianes.php");
                }
                    
                
            }catch(Exception $ex){

                $alert = new Alert("danger",$ex->getMessage());
                $listaGuardianes = $guardianDAO->GetAll();
                require_once(VIEWS_PATH . "DashboardDueno/Guardianes.php");
                
            }

             
        }else{

            header("location: ../Home");
        }

       
    }

    public function VerPerfilGuardian($idGuardian){ //CHECKED
        
        $guardianDAO = new GuardianDAO(); 
        
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){
            
            try{

                $guardian=$guardianDAO ->devolverGuardianPorId($idGuardian);
                $tamaños=$guardianDAO ->obtenerTamañosMascotas($guardian->getId());
                $fotopuntaje = $this->FotoValoracion($guardian->getCalificacion());

                require_once(VIEWS_PATH . "DashboardDueno/PerfilGuardian.php");


            }catch(Exception $ex){
       
                header("location: ../Duenos/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }
   
        }else{

            header("location: ../Home");
            
        }
    }

    public function vistaFavoritos($alert = null) //CHECKED
    {

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            $guardianesDAO = new GuardianDAO();

            try{

                $listaGuardianes = $guardianesDAO->getFavoritos();

                if($listaGuardianes){

                    require_once(VIEWS_PATH . "DashboardDueno/Favoritos.php");

                }else{

                    throw new Exception("No tiene guardianes favoritos");
                }
                

            }catch(Exception $ex){

                $alert=new Alert("danger", $ex->getMessage());       
                require_once(VIEWS_PATH . "DashboardDueno/Favoritos.php");
            }


        }else{

            header("location: ../Home");
        }
    }

    public function AgregarFavorito($id){ //CHECKED

        $usuarioDAO=new UserDAO();

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            try{

                if($usuarioDAO->addFavorito($id)){

                    header("location: ../Duenos/VistaFavoritos");

                }
                throw new Exception("El guardian ya esta en favoritos");

            }catch(Exception $ex){

                $alert=new Alert("danger", $ex->getMessage());        
                $this->vistaFavoritos($alert);
            }

        }else{

            header("location: ../Home");
        }
    }

    public function BorrarFavorito($idGuardian) //CHECKED
    {
        $usuarioDAO = new UserDAO();

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {
        
            try{
                
                if($usuarioDAO->deleteFavorito($idGuardian)){

                    header("location: ../Duenos/VistaFavoritos");

                }else{

                   throw new Exception("Error al eliminar el guardian");
                }
                
                

            }catch (Exception $ex){

                $alert=new Alert("danger", $ex->getMessage());       
                $this->vistaFavoritos($alert);
            }

        }else{

            header("location: ../Home");
        }
    }  

    public function FiltrarGuardianes($fechaMin, $fechaMax, $nombreGuardian){ //CHECKED
 
        $guardianDAO = new GuardianDAO();

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){
      
            $resultado= null;
           
            try{

                if(empty($nombreGuardian) and (!empty($fechaMin) and !empty($fechaMax))){

                    $resultado = $guardianDAO->getGuardianesFiltradosFecha($fechaMin,$fechaMax);

                    if($resultado){

                        return $resultado;

                    }else{

                        throw new Exception("No se encontró un guardian para esas fechas");
                    }
                               
                }else{

                    $resultado = $guardianDAO->getGuardianPorNombre($nombreGuardian);

                    if($resultado){

                        return $resultado;

                    }else{

                        throw new Exception("No se encontró a un guardian que coincida");
                    }
    
                }

            }catch(Exception $ex){

                throw $ex;
            }

        }else{

            header("location: ../Home");
        }
    }  

    public function VistaReviews($idGuardian, $alert=null){


        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            $ReviewDAO = new ReviewDAO();
        
            try{
                
                $listaReviews = $ReviewDAO->devolverReviewsGuardian($idGuardian);
                

                if($listaReviews){

                    require_once(VIEWS_PATH . "DashboardDueno/Reviews.php");

                }else{

                    throw new Exception("El guardian no posee reviews");
                }      

            }catch (Exception $ex){
  
                $alert = new Alert("danger", $ex->getMessage());
                require_once(VIEWS_PATH . "DashboardDueno/Reviews.php");
            }

        }else{

            header("location: ../Home");
        }


    }


    public function CrearReview($calificacion, $comentario, $idGuardian, $idReserva){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {
        
            $review = new Review();
            $ReviweDAO = new ReviewDAO();
            $type= "danger";
            
            try{
            
                $review->setDueño($_SESSION["UserId"]);
                $review->setCalificacion($calificacion);
                $review->setComentario($comentario);
                $review->setGuardian($idGuardian);
                $review->setReserva($idReserva);

                if($ReviweDAO->crearReview($review)){

                    header("location: ../Duenos/VistaReviews?idGuardian=".$idGuardian);
    
                }else{

                    throw new Exception("Error al crear la review. Intente mas tarde");
                }

                
            }catch (Exception $ex){
  
            
            }

        }else{

            header("location: ../Home");
        }
        
    }

    private function FotoValoracion($puntaje){
        
        if($puntaje>=1 and $puntaje<2){

            return "1_stars.png";
        }
        else if($puntaje>=2 and $puntaje<3){

            return "2_stars.png";

        }
        else if($puntaje>=3 and $puntaje<4){

            return "3_stars.png";

        }
        else if($puntaje>=4 and $puntaje<4.7){

            return "4_stars.png";

        }
        else{

            return "5_stars.png";
        }
        
        
    }
}

