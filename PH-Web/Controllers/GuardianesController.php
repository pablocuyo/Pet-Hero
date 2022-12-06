<?php

namespace Controllers;

use DAO\GuardianDAO as GuardianDAO;
use DAO\MascotaDAO;
use Models\Guardian as Guardian;
use DAO\UserDAO as UserDAO;
use Models\Alert as Alert;
use Exception;
use Models\Archivos;

class GuardianesController
{

    private $GuardianDAO;
    private $UserDAO;
    

    public function __construct()
    {
        $this->GuardianDAO = new GuardianDAO();
        $this->UserDAO = new UserDAO();
    }


    public function VistaDashboard($alert=null, $tipo=null){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            require_once(VIEWS_PATH . "DashboardGuardian/Dashboard.php");

        }else{

            header("location: ../Home");
        }
    }

     /* FUNCIONES DE REGISTRO */

    public function VistaRegistro($alert = null) //CHECKED
    {

         require_once(VIEWS_PATH . "RegistroGuardian.php");
        
    }

    public function RegistrarDisponibilidad($alert = null) //CHECKED
    {
        if(isset($_SESSION["GuardTemp"])){

            require_once(VIEWS_PATH . "RegistroDisponibilidad.php");

        }else{

            header("location: ../Home");
        }
    }

    public function Add($inicio, $fin, $sizes, $costo, $fotoEspacio, $descripcion) //CHECKED
    {

        if(isset($_SESSION["GuardTemp"])){

            $guardian =  unserialize($_SESSION["GuardTemp"]);

            unset($_SESSION["GuardTemp"]);

            $guardian->setFechaInicio($inicio);
            $guardian->setFechaFin($fin);

            foreach ($sizes as $size) {
                $guardian->pushTipoMascota($size);
            }

            $guardian->setCosto($costo);

            $nameImg = $guardian->getUsername() ."-". $fotoEspacio["name"];

            $guardian->setFotoEspacioURL($nameImg);
            $guardian->setDescripcion($descripcion);


            try{

                if($this->UserDAO->addGuardian($guardian)){

                    Archivos::subirArch("fotoEspacio", $fotoEspacio, "EspaciosGuardianes/", $guardian->getUsername());
                    header("location: ../Home?alert=Perfil creado con exito. Puede loguearse&tipo=success");

                }else{
                    
                    throw new Exception("Error de servidor, intente nuevamente"); 
                }
                
            }catch (Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->RegistrarDisponibilidad($alert);

            }

        }else{

            header("location: ../Home");
        }
    }

    public function Registro($username, $nombre, $apellido, $dni, $mail, $telefono, $direccion, $password, $rePassword, $fotoPerfil=null) //CHECKED
    {

        $guardian = new Guardian();
        $guardian->setUsername($username);
        $guardian->setDni($dni);
        $guardian->setNombre($nombre);
        $guardian->setApellido($apellido);
        $guardian->setCorreoelectronico($mail);
        $guardian->setTelefono($telefono);
        $guardian->setDireccion($direccion);
        
        if(empty($fotoPerfil["name"])){
            
            $nameImg="perfil-default.png";

        }else{

            $nameImg = $guardian->getUsername() ."-". $fotoPerfil["name"];

        }               
        
        $guardian->setFotoPerfil($nameImg);


        $type = null;

        try{

            if (!$this->UserDAO->checkUsuario($username, $dni, $mail)) {

                if ($password == $rePassword) {
                    
                    Archivos::subirArch("fotoPerfil", $fotoPerfil, "FotosUsuarios/", $guardian->getUsername());
    
                    $guardian->setPassword($password);
    
                    $_SESSION["GuardTemp"] = serialize($guardian);
    
                    header("location: ../Guardianes/RegistrarDisponibilidad");
    
                }else{
                    
                    $type = "danger";
                    throw new Exception("Las contraseñas no coinciden");
                }

                
            }else{
                
                $type = "danger";
                throw new Exception("El usuario ya existe");
            }

        }catch (Exception $ex){

            $alert = new Alert($type, $ex->getMessage());
            $this->VistaRegistro($alert);

        }
   
    }
    
    /* FUNCIONES DE DISPONIBILIDAD Y PERFIL */
    public function EditarDisponibilidad($alert = null){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            try{

                $guardian=$this->GuardianDAO->devolverGuardianPorId($_SESSION["UserId"]);
                
                if($guardian){
                    
                    require_once(VIEWS_PATH . "/DashboardGuardian/EditarDisponibilidad.php");

                }else{
                    
                    throw new Exception("Error al cargar usuario");
                }
            
            }catch(Exception $ex){

                header("location: ../Guardianes/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }

        }else{

            header("location: ../Home");
        }
    }

    public function ActualizarDisponibilidad($fechaInicio,$fechaFin,$sizes,$costo,$fotoUrl,$descripcion){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){
        
            $type = "danger";

            try {

                if($this->GuardianDAO->grabarDisponibilidad($fechaInicio,$fechaFin,$sizes,$costo,$fotoUrl,$descripcion)){

                    $type= "success";
                    throw new Exception("Disponibilidad cambiada con exito");

                }else{
                    
                    throw new Exception("No se pudieron actulizar los datos");
                }

                  
            } catch (Exception $ex) {
                
                $alert = new Alert($type, $ex->getMessage());
                $this->EditarDisponibilidad($alert);
            }
            
        }else{

            header("location: ../Home");
        }
    }

    public function EditarPerfil(){ //CHECKED
        
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){
        
            try{

                $usuario = $this->GuardianDAO->devolverGuardianPorId($_SESSION["UserId"]);

                if($usuario){

                    require_once(VIEWS_PATH . "DashboardGuardian/EditarPerfil.php");

                }else{
                    
                    throw new Exception("Error al editar perfil");
                }


            }catch(Exception $ex){

                header("location: ../Guardianes/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }   

        }else{

            header("location: ../Home");
        }
    }

    /* FUNCIONES DE SOLICITUDES */

    public function VistaSolicitudes(){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            require_once(VIEWS_PATH. "DashboardGuardian/Solicitudes.php");

        }else{

            header("location: ../Home");
        }
    }
    

}

