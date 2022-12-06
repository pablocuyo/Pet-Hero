<?php
namespace DAO;


use Models\Mensaje as Mensaje;
use \Exception as Exception;
use Models\Inbox as Inbox;

class MensajeDAO{

    private $connection;

    function __construct(){
        
    }

    public function GetMsg($id){ //CHECKED
        
      $listaMensajes = array();
      $query = "CALL listar_chat(:id_sesion, :id_interlocutor);";  
      
      
        $parameters["id_sesion"]=$_SESSION["UserId"];
        $parameters["id_interlocutor"]=$id;

        $this->connection = Connection::GetInstance();

        try {

            $resultSet = $this->connection->Execute($query,$parameters);

            foreach($resultSet as $reg){

                $mensaje=new Mensaje();
                $mensaje->setFecha($reg["fecha"]);
                $mensaje->setEmisor($reg["id_emisor"]);
                $mensaje->setReceptor($reg["id_receptor"]);
                $mensaje->setContenido($reg["contenido"]);
                

                array_push($listaMensajes,$mensaje);

            }
            return $listaMensajes;

        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function traerBandeja(){ //CHECKED

        $bandeja=array();

        $query="SELECT m.fecha,m.id_emisor, u.username from mensajes m
        inner join usuarios u on u.id_usuario=m.id_emisor
        where (id_receptor=:id_session)
        group by u.username ;";

        $parameters["id_session"]=$_SESSION["UserId"];

        $this->connection = Connection::GetInstance();

        try {

            $resultSet = $this->connection->Execute($query,$parameters);
           
            foreach($resultSet as $reg){

                $inbox=new Inbox();
                $inbox->setFecha($reg["fecha"]);
                $inbox->setId($reg["id_emisor"]);
                $inbox->setNombre($reg["username"]);
                array_push($bandeja,$inbox);
                
            }
            return $bandeja;

        }catch (Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    
    }

    public function Add($mensaje){ //CHECKED
        
        $query="CALL nuevo_mensaje(:emisor,:receptor,:contenido);";

        $parameters["emisor"]=$mensaje->getEmisor();;
        $parameters["receptor"]=$mensaje->getReceptor();
        $parameters["contenido"]=$mensaje->getContenido();
        
        $this->connection = Connection::GetInstance();   

        try {

            return $this->connection->ExecuteNonQuery($query, $parameters);
            
        }
        catch (Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    
    }

}

?>