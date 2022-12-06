<?php
namespace Controllers;

use DAO\UserDAO;
use Exception;

class UsuarioController{

    private $userDAO;

    function __construct(){

        $this->userDAO=new UserDAO();
    }


    public function ActualizarDatos($telefono,$direccion,$password,$rePassword){ //CHECKED
        
        if(isset($_SESSION["UserId"])){

            try{

                if($password==$rePassword){
                
                    $this->userDAO->grabarDatosActualizados($telefono,$direccion,$password);
                
                    header("location: ../Home");

                }else{
                    throw new Exception("Contraseña cambiada incorrectamente");
                }


            }catch(Exception $ex){

                $dash = $_SESSION["Tipo"]=="D" ? "Duenos" : "Guardianes";

                header("location: ../".$dash."/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");

            }
            
        }else{

            header("location: ../Home");
        }

    }


}



?>



