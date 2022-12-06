<?php 
namespace Controllers;

use Models\Mensaje as Mensaje;
use DAO\MensajeDAO as MensajeDAO;
use DAO\UserDAO as UserDAO;
use Exception;

class MensajeController{

    private $mensajeDAO;
    private $usersDAO;

    public function __construct()
    {
        $this->mensajeDAO=new MensajeDAO();
        $this->usersDAO = new UserDAO();
    }

    public function VistaChat($id){ //CHECKED
        
        if(isset($_SESSION["UserId"])){

            try{

                $listaMensajes=$this->mensajeDAO->GetMsg($id);

                if($listaMensajes){

                    $usuario=$this->interlocutor($listaMensajes[0]);

                    if($usuario){

                        if($_SESSION["Tipo"]=="D"){
                        
                            require_once(VIEWS_PATH."/DashboardDueno/Mensajes.php");
                            
                        }else{
        
                            require_once(VIEWS_PATH."/DashboardGuardian/Mensajes.php");
                        }


                    }else{
                        
                        throw new Exception("Error al encontrar los mensajes enviados por ti");
                    }


                }else{

                    throw new Exception("Error al encontrar los mensajes");
     
                }


            }
            catch (Exception $ex){

                $dash = $_SESSION["Tipo"]=="D" ? "Duenos" : "Guardianes";

                header("location: ../".$dash."/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }
           
        }else{

            header("location: ../Home");
        }
    }

    public function Interlocutor($mensaje){ //CHECKED

        if(isset($_SESSION["UserId"])){
            
            try{


                if($mensaje->getEmisor() != $_SESSION["UserId"]){
                
                    $usuario=$this->usersDAO->retornarUsuarioPorId($mensaje->getEmisor());
                    
                }else{
    
                    $usuario=$this->usersDAO->retornarUsuarioPorId($mensaje->getReceptor());
                }

                return $usuario;

            }
            catch (Exception $ex){

                $dash = $_SESSION["Tipo"]=="D" ? "Duenos" : "Guardianes";

                header("location: ../".$dash."/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }
        
        }else{

            header("location: ../Home");
        }
    }

    public function BandejaEntrada(){ //CHECKED

        if(isset($_SESSION["UserId"])){

            try{

                $bandeja=$this->mensajeDAO->traerBandeja();

                if($bandeja){

                    switch($_SESSION["Tipo"]){

                        case "D":
                            require_once(VIEWS_PATH."/DashboardDueno/verMensajes.php");
                        break;

                        case "G":
                            require_once(VIEWS_PATH."/DashboardGuardian/verMensajes.php");
                        break;
                    }
    
                }else{
    
                    switch($_SESSION["Tipo"]){

                        case "D":
                            require_once(VIEWS_PATH."/DashboardDueno/Dashboard.php");
                        break;

                        case "G":
                            require_once(VIEWS_PATH."/DashboardGuardian/Dashboard.php");
                        break;
                        }
                } 

            }
            catch (Exception $ex){

                $dash = $_SESSION["Tipo"]=="D" ? "Duenos" : "Guardianes";

                header("location: ../".$dash."/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");
            }
      
        }else{
            header("location: ../Home");
        }
            
   
    }

    public function NuevoMensaje($id,$nombre){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"]=="D"){
     
            require_once(VIEWS_PATH."DashboardDueno/enviarNuevoMensaje.php");

        }else{
            header("location: ../Home");
        }         
    }

    public function Add($id,$chat){ //CHECKED
        
        if(isset($_SESSION["UserId"])){
        
            $mensaje=new Mensaje();
            $mensaje->setEmisor($_SESSION["UserId"]);
            $mensaje->setReceptor($id);
            $mensaje->setContenido($chat);

            try{
                
                if($this->mensajeDAO->Add($mensaje)){
                    
                    header("location: ../Mensaje/vistaChat?id=" .$id);
                                    
                }else{

                    throw new Exception("No se pudo enviar el mensaje"); 
                }


            }catch (Exception $ex){
                
                $dash = $_SESSION["Tipo"]=="D" ? "Duenos" : "Guardianes";

                header("location: ../".$dash."/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");

            }

        }else{

            header("location: ../Home");
        } 
    }
}
?>