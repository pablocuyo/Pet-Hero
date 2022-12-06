<?php
namespace Controllers;
use DAO\UserDAO as UserDAO;
use \Exception as Exception;
use Models\Alert;

class HomeController{
    
    private $UserDAO;
    
    public function __construct()
    {
        $this->UserDAO = new UserDAO();
    }
    
    public function Index($alert=null){ 

        require_once(VIEWS_PATH."index.php");

    }

    public function DashDuenoView(){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            require_once(VIEWS_PATH."DashboardDueno/Dashboard.php");

        }else{

            header("location: ../Home");
        }
        
    }

    public function DashGuardianView(){ //CHECKED

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            require_once(VIEWS_PATH."DashboardGuardian/Dashboard.php");

        }else{

            header("location: ../Home");
        }
    }

    public function Login($username, $password){ //CHECKED

        
        try{

            $usuario = $this->UserDAO->retornarUsuarioPorNombre($username);

            if($usuario){
            
                if($usuario->getPassword() == $password){

                    switch($usuario->getTipoUsuario()){
    
                        case "G":
                        $_SESSION["UserId"] = $usuario->getId();
                        $_SESSION["Tipo"]=$usuario->getTipoUsuario();
                        $this->DashGuardianView();
                        break;
            
                        case "D":
                        $_SESSION["UserId"] = $usuario->getId();
                        $_SESSION["Tipo"]=$usuario->getTipoUsuario();
                        $this->DashDuenoView();
                        break;
                    }

                }
                else{

                    throw new Exception("Contraseña Incorrecta");
                }   
            }
            else{
                
                throw new Exception("Usuario o contraseña incorrecta. Si no tiene una cuenta, registrese");
            }

        }catch(Exception $ex){

            header("location: ../Home?tipo=danger&alert=".$ex->getMessage());

        }
            
        
    }

    public function LogOut(){ //CHECKED
        
        unset($_SESSION["UserId"]);
        unset($_SESSION["Tipo"]);
        header("location: ../Home");
    }

    public function Eleccion($alert = null){ //CHECKED
        
        require_once(VIEWS_PATH."FiltroRegistro.php");
    }
}
?>