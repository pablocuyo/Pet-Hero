<?php
namespace DAO;

use DAO\Connection;
use Models\Dueño as Dueño;
use DAO\InterfaceDAO as InterfaceDAO;
use DAO\UserDAO as UserDAO;
use Exception;

class DueñoDAO implements InterfaceDAO{
    
    private $connection;

    public function GetAll(){

        /*
        $dueñosList = array();

        $query = "SELECT 
        d.id_dueño,
        u.username,
        u.dni,
        u.nombre,
        u.apellido,
        u.correo,
        u.contraseña,
        u.telefono,
        u.foto_perfil
        FROM
        usuarios u
        INNER JOIN
        dueños d ON d.id_usuario = u.id_usuario";

        $this->connection = Connection::GetInstance();
        
        try{

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $row){

                $dueño = new Dueño();

                $dueño->setId($row["id_dueño"]);
                $dueño->setUsername($row["username"]);
                $dueño->setNombre($row["nombre"]);
                $dueño->setApellido($row["apellido"]);
                $dueño->setDni($row["dni"]);
                $dueño->setDireccion($row["direccion"]);
                $dueño->setTelefono($row["telefono"]);
                $dueño->setCorreoelectronico($row["correo"]);

                array_push($dueñosList, $dueño);

            }

            return $dueñosList;

        }
        catch(Exception $ex){

            throw $ex;
        }
        */
    }

    
    
    public function add($dueño){  //CHECKED

        $query = "INSERT INTO 
        dueños (id_usuario) 
        values ((select id_usuario from usuarios where username = :username));";

        $parameters["username"] = $dueño->getUsername();

        $this->connection = Connection::GetInstance();

        try {
            return $this->connection->ExecuteNonQuery($query, $parameters);
            
        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    }

    public function devolverDueñoPorId($usuarioId){ //CHECKED

    
        $query = "SELECT 
        * 
        FROM usuarios u 
        inner join dueños d 
        on u.id_usuario = d.id_usuario
        where d.id_usuario = :id_usuario";

        $parameters["id_usuario"] = $usuarioId;


        $this->connection = Connection::GetInstance();

        try{
            
            $resultSet = $this->connection->Execute($query, $parameters);

            $dueño = null;

            if($resultSet){

                
                $reg = $resultSet[0];

                $dueño = new Dueño();
                $dueño->setId($usuarioId);
                $dueño->setUsername($reg["username"]);
                $dueño->setDni($reg["dni"]);
                $dueño->setNombre($reg["nombre"]);
                $dueño->setApellido($reg["apellido"]);
                $dueño->setCorreoelectronico($reg["correo"]);
                $dueño->setPassword($reg["password"]);
                $dueño->setTelefono($reg["telefono"]);
                $dueño->setDireccion($reg["direccion"]);
                $dueño->setFotoPerfil($reg["foto_perfil"]);
                $dueño->setTipoUsuario($reg["tipo_usuario"]);

            }

            return $dueño; 
        
        }catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
        

    }

    public function devolverUsuarioPorDueño($idDueño){ //CHECKED

        $dueño = new Dueño();

        $query = "SELECT 
        *
        FROM  
        usuarios u
        inner join 
        dueños d 
        on
        d.id_usuario = u.id_usuario  
        where d.id_dueño = :id_dueño;";

        $parameters["id_dueño"] = $idDueño;

        $this->connection = Connection::GetInstance();


        try{
            
            $resultSet = $this->connection->Execute($query,$parameters);

            if($resultSet){

                $reg = $resultSet[0];

                $dueño->setId($reg["id_usuario"]);
                $dueño->setUsername($reg["username"]);
                $dueño->setDni($reg["dni"]);
                $dueño->setNombre($reg["nombre"]);
                $dueño->setApellido($reg["apellido"]);
                $dueño->setCorreoelectronico($reg["correo"]);
                $dueño->setPassword($reg["password"]);
                $dueño->setTelefono($reg["telefono"]);
                $dueño->setDireccion($reg["direccion"]);
                $dueño->setFotoPerfil($reg["foto_perfil"]);
                $dueño->setTipoUsuario($reg["tipo_usuario"]);

            }

            return $dueño; 
        
        }catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    }

}