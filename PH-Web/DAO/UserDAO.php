<?php
namespace DAO;

use DAO\Connection;

use Models\Usuario as Usuario;
use Exception;
use DAO\GuardianDAO as GuardianDAO;

class UserDAO{
    
    private $connection;

    
    public function retornarUsuarioPorId($id){


        $query = "SELECT 
        u.id_usuario,
        u.username,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        u.direccion,
        u.tipo_usuario
        FROM
            usuarios u
        WHERE
            u.id_usuario = :id;";

        $parameters["id"] = $id;
    
        $this->connection = Connection::GetInstance();
        
        
        try {

            $resultSet = $this->connection->Execute($query, $parameters);

            $usuario = new Usuario();

            if($resultSet){

                $reg = $resultSet[0];

                $usuario->setId($reg["id_usuario"]);
                $usuario->setUsername($reg["username"]);
                $usuario->setNombre($reg["nombre"]);
                $usuario->setApellido($reg["apellido"]);
                $usuario->setCorreoelectronico($reg["correo"]);
                $usuario->setTelefono($reg["telefono"]);
                $usuario->setDireccion($reg["direccion"]);
                $usuario->setTipoUsuario($reg["tipo_usuario"]);
  
            }

            return $usuario; 
               
        } catch (Exception $ex) {

            throw $ex;
        }

    }

    public function retornarUsuarioPorNombre($username){ //CHECKED

        $query = "SELECT 
        u.id_usuario,
        u.username,
        u.dni,
        u.nombre,
        u.apellido,
        u.correo,
        u.password,
        u.telefono,
        u.direccion,
        u.foto_perfil, 
        u.tipo_usuario
        FROM
            usuarios u
        WHERE
            u.username = :username;";

        $parameters["username"] = $username;
    
        $this->connection = Connection::GetInstance();
        
        
        try {

            $resultSet = $this->connection->Execute($query, $parameters);

            $usuario = null;

            if($resultSet){

                $reg = $resultSet[0];

                $usuario = new Usuario();

                $usuario->setId($reg["id_usuario"]);
                $usuario->setUsername($reg["username"]);
                $usuario->setDni($reg["dni"]);
                $usuario->setNombre($reg["nombre"]);
                $usuario->setApellido($reg["apellido"]);
                $usuario->setCorreoelectronico($reg["correo"]);
                $usuario->setPassword($reg["password"]);
                $usuario->setTelefono($reg["telefono"]);
                $usuario->setDireccion($reg["direccion"]);
                $usuario->setFotoPerfil($reg["foto_perfil"]);
                $usuario->setTipoUsuario($reg["tipo_usuario"]);
                
            }

            
            return $usuario; //Retorna null si no existe o devuelve el objeto en caso de existir
               
        } catch (Exception $ex) {

            throw $ex;
        }

    }

    public function checkUsuario($username, $dni, $correo)  //CHECKED
    {

        $query = "SELECT 
        u.username, u.dni, u.correo
        FROM
            usuarios u
        WHERE
            u.username = :username or u.dni= :dni or u.correo = :correo;";

        $parameters["username"] = $username;
        $parameters["dni"] = $dni;
        $parameters["correo"] = $correo;
        
        try {

            $this->connection = Connection::GetInstance();

            return $this->connection->Execute($query, $parameters);
            
        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function addGuardian(Usuario $guardian) //CHECKED
    {

        $guardianDAO = new GuardianDAO();

        $query = "INSERT INTO 
        usuarios (username, dni, nombre, apellido, correo, password, telefono, direccion, foto_perfil, tipo_usuario) 
        VALUES(:username, :dni, :nombre, :apellido, :correo, :password, :telefono, :direccion, :foto_perfil, :tipo_usuario);";

        $parameters["username"] = $guardian->getUsername();
        $parameters["dni"] = $guardian->getDni();
        $parameters["nombre"] = $guardian->getNombre();
        $parameters["apellido"] = $guardian->getApellido();
        $parameters["correo"] = $guardian->getCorreoelectronico();
        $parameters["password"] = $guardian->getPassword();
        $parameters["telefono"] = $guardian->getTelefono();
        $parameters["direccion"] = $guardian->getDireccion();
        $parameters["foto_perfil"] = $guardian->getFotoPerfil(); 
        $parameters["tipo_usuario"] = "G";

        $this->connection = Connection::GetInstance();

        try {

            $this->connection->ExecuteNonQuery($query, $parameters);

            return $guardianDAO->Add($guardian);
            
        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }
    
    public function addDueño(Usuario $dueño) //CHECKED
    {
    
        $query = "INSERT INTO 
        usuarios (username, dni, nombre, apellido, correo, password, telefono, direccion, foto_perfil, tipo_usuario) 
        VALUES(:username, :dni, :nombre, :apellido, :correo, :password, :telefono, :direccion, :foto_perfil, :tipo_usuario);";

        $parameters["username"] = $dueño->getUsername();
        $parameters["dni"] = $dueño->getDni();
        $parameters["nombre"] = $dueño->getNombre();
        $parameters["apellido"] = $dueño->getApellido();
        $parameters["correo"] = $dueño->getCorreoelectronico();
        $parameters["password"] = $dueño->getPassword();
        $parameters["telefono"] = $dueño->getTelefono();
        $parameters["direccion"] = $dueño->getDireccion();
        $parameters["foto_perfil"] = $dueño->getFotoPerfil(); 
        $parameters["tipo_usuario"] = "D";

        $this->connection = Connection::GetInstance();

        try {

            return $this->connection->ExecuteNonQuery($query, $parameters);

        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function addFavorito($id){ //CHECKED
            
        $query = "CALL agregar_favorito( :id_usuario, :id);";

        $parameters["id_usuario"] = $_SESSION["UserId"];
        $parameters["id"] = $id;
            
        $this->connection = Connection::GetInstance();
            
        try{

            return $this->connection->ExecuteNonQuery($query, $parameters);
         
        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }
    
    public function deleteFavorito($idGuardian){ //CHECKED
        
        $query = "CALL borrar_favorito( :id_usuario, :id_guardian);";

        $parameters["id_usuario"] = $_SESSION["UserId"];
        $parameters["id_guardian"] = $idGuardian;
            
        $this->connection = Connection::GetInstance();
        
        try{

            return $this->connection->ExecuteNonQuery($query, $parameters);
              
        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        
        }

    }
    
    public function grabarDatosActualizados($telefono,$direccion,$password){
       
        $query = "UPDATE usuarios u
        set u.telefono = :telefono, u.direccion=:direccion, u.password=:password WHERE u.id_usuario=:buscado";

        $parameters["telefono"] = $telefono;
        $parameters["direccion"] = $direccion;
        $parameters["password"] = $password;
        $parameters["buscado"] = ($_SESSION["UserId"]);
             
        try{

            $this->connection = Connection::GetInstance();

            $this->connection->ExecuteNonQuery($query, $parameters);


        }
        catch (Exception $ex) {
            throw $ex;
        }

    }
    

}
