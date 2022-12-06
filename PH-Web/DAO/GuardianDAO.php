<?php

namespace DAO;

use DAO\Connection;
use Models\Guardian as Guardian;
use Models\Usuario as Usuario;
use DAO\InterfaceDAO as InterfaceDAO;
use \Exception as Exception;

class GuardianDAO implements InterfaceDAO
{

    private $connection;

    public function GetAll()
    {
        $guardianesList = array();

        $query = "SELECT 
        g.id_usuario,
        u.username,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        u.foto_perfil,
        g.dia_inicio,
        g.dia_fin,
        g.descripcion,
        g.costo_diario,
        g.foto_espacio
        FROM
        usuarios u
        INNER JOIN
        guardianes g ON g.id_usuario = u.id_usuario";


        $this->connection = Connection::GetInstance();

        try {

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $reg){

                $guardian=new Guardian();
                $guardian->setId($reg["id_usuario"]);
                $guardian->setUsername($reg["username"]);
                $guardian->setNombre($reg["nombre"]);
                $guardian->setApellido($reg["apellido"]);
                $guardian->setCorreoelectronico($reg["correo"]);
                $guardian->setTelefono($reg["telefono"]);
                $guardian->setFotoPerfil($reg["foto_perfil"]);
                $guardian->setFechaInicio($reg["dia_inicio"]);
                $guardian->setDescripcion($reg["descripcion"]);
                $guardian->setFechaFin($reg["dia_fin"]);
                $guardian->setCosto($reg["costo_diario"]);
                $guardian->setFotoEspacioURL($reg["foto_espacio"]);
                $guardian->setTipoMascota($this->obtenerTamañosMascotas($reg["id_usuario"]));

                array_push($guardianesList,$guardian);

            }
            return $guardianesList;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    public function getFavoritos() //CHECKED
    {
        $listaFavoritos = array();

        $query = "SELECT
        g.id_guardian, 
        g.id_usuario,
        u.username,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        u.foto_perfil,
        g.dia_inicio,
        g.dia_fin,
        g.descripcion,
        g.costo_diario,
        g.foto_espacio
        from favoritos f
        inner join guardianes g on f.id_guardianFav=g.id_guardian
        inner join usuarios u on g.id_usuario=u.id_usuario
        inner join dueños d on f.id_dueño=d.id_dueño";


        $this->connection = Connection::GetInstance();


        try {

            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $reg){

                $guardian=new Guardian();
                $guardian->setId($reg["id_usuario"]);
                $guardian->setUsername($reg["username"]);
                $guardian->setNombre($reg["nombre"]);
                $guardian->setApellido($reg["apellido"]);
                $guardian->setCorreoelectronico($reg["correo"]);
                $guardian->setTelefono($reg["telefono"]);
                $guardian->setFotoPerfil($reg["foto_perfil"]);
                $guardian->setFechaInicio($reg["dia_inicio"]);
                $guardian->setDescripcion($reg["descripcion"]);
                $guardian->setFechaFin($reg["dia_fin"]);
                $guardian->setCosto($reg["costo_diario"]);
                $guardian->setFotoEspacioURL($reg["foto_espacio"]);
                $guardian->setTipoMascota($this->obtenerTamañosMascotas($reg["id_usuario"]));

                array_push($listaFavoritos,$guardian);

            }
            return $listaFavoritos;

        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function add($guardian) //CHECKED
    {
        $query = "INSERT INTO 
        guardianes (id_usuario, dia_inicio, dia_fin, descripcion, costo_diario, foto_espacio) 
        values ((select id_usuario from usuarios where username = :username), 
        :dia_inicio, :dia_fin, :descripcion, :costo_diario, :foto_espacio)";

        $parameters["username"] = $guardian->getUsername();
        $parameters["dia_inicio"] = $guardian->getFechaInicio();
        $parameters["dia_fin"] = $guardian->getFechaFin();
        $parameters["descripcion"] = $guardian->getDescripcion();
        $parameters["costo_diario"] = $guardian->getCosto();
        $parameters["foto_espacio"] = $guardian->getFotoEspacioURL();

        $this->connection = Connection::GetInstance();

        try {


            $this->connection->ExecuteNonQuery($query, $parameters);

            foreach ($guardian->getTipoMascota() as $tamaño) {

    
                $query = "INSERT INTO tamaños_x_guardianes(id_tamaño, id_guardian) 
                    VALUES ((select id_tamaño from tamaños where nombre_tamaño = :tamano), 
                    (select g.id_guardian from guardianes g inner join usuarios u on u.id_usuario = g.id_usuario where u.username = :username));";

                $parametersTam["tamano"] = $tamaño;
                $parametersTam["username"] = $guardian->getUsername();


                $resultado = $this->connection->ExecuteNonQuery($query, $parametersTam);
            }


            return $resultado;
            
        } catch (Exception $ex) {
            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function devolverGuardianPorId($idUsuario){ //CHECKED
        
        $guardian = null;

        $query = "SELECT 
        * ,
        ifnull(avg(r.calificacion),1) as puntaje
        FROM usuarios u
        inner join guardianes g 
        on u.id_usuario = g.id_usuario
        inner join reviews r on r.id_guardian = u.id_usuario
        where u.id_usuario = :id_usuario;";

        $parameters["id_usuario"] = $idUsuario;

        $this->connection = Connection::GetInstance();

        try{
        
            $resultSet = $this->connection->Execute($query, $parameters);

            if($resultSet){

                $reg = $resultSet[0];

                $guardian = new Guardian();

                $guardian->setId($reg["id_usuario"]);
                $guardian->setUsername($reg["username"]);
                $guardian->setDni($reg["dni"]);
                $guardian->setNombre($reg["nombre"]);
                $guardian->setApellido($reg["apellido"]);
                $guardian->setCorreoelectronico($reg["correo"]);
                $guardian->setPassword($reg["password"]);
                $guardian->setTelefono($reg["telefono"]);
                $guardian->setDireccion($reg["direccion"]);
                $guardian->setFotoPerfil($reg["foto_perfil"]);
                $guardian->setTipoUsuario($reg["tipo_usuario"]);
                $guardian->setFechaInicio($reg["dia_inicio"]);
                $guardian->setFechaFin($reg["dia_fin"]);
                $guardian->setDescripcion($reg["descripcion"]);
                $guardian->setCosto($reg["costo_diario"]);
                $guardian->setFotoEspacioURL($reg["foto_espacio"]);
                $guardian->setCalificacion($reg["puntaje"]);
                $guardian->setTipoMascota($this->obtenerTamañosMascotas($reg["id_usuario"]));


            }       
                
            return $guardian;
            
        }catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function obtenerTamañosMascotas($idUsuario){ //CHECKED

        $listaTamaños = array();

        $query = "SELECT 
        t.nombre_tamaño
        FROM
        tamaños t
        INNER JOIN
        tamaños_x_guardianes txg ON txg.id_tamaño = t.id_tamaño
        INNER JOIN
        guardianes g ON txg.id_guardian = g.id_guardian
        INNER JOIN
        usuarios u on u.id_usuario = g.id_usuario
        WHERE u.id_usuario= :id_usuario;";

        $parameters["id_usuario"] = $idUsuario;
          
        $this->connection = Connection::GetInstance();

        try{

            $resultSet = $this->connection->Execute($query, $parameters);

            foreach ($resultSet as $reg){
    
                array_push($listaTamaños, $reg["nombre_tamaño"]);
    
            }
    
            return $listaTamaños;


        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    }

    public function grabarDisponibilidad($fechaInicio,$fechaFin,$sizes,$costo,$fotoUrl,$descripcion){ //CHECKED

        $resultado=null;

        $queryUpdate = "UPDATE guardianes g
        set g.dia_inicio = :fechaInicio, g.dia_fin=:fechaFin, g.costo_diario=:costo,g.foto_espacio=:fotoUrl, g.descripcion=:descripcion WHERE g.id_usuario=:buscado";

        $parameters["fechaInicio"] = $fechaInicio;
        $parameters["fechaFin"] = $fechaFin;
        $parameters["costo"] = $costo;
        $parameters["fotoUrl"] = $fotoUrl;
        $parameters["descripcion"] = $descripcion;
        $parameters["buscado"] = ($_SESSION["UserId"]);

        $queryDelete = "DELETE from tamaños_x_guardianes
        where id_guardian = (select g.id_guardian from guardianes g inner join usuarios u on u.id_usuario = g.id_usuario where g.id_usuario= :id_usuario );";

        $parametersDel["id_usuario"] = $_SESSION["UserId"];

        $this->connection = Connection::GetInstance();

        try{

                $this->connection->ExecuteNonQuery($queryUpdate, $parameters);
                                   
                $this->connection->ExecuteNonQuery($queryDelete, $parametersDel);


               foreach ($sizes as $tamaño) {
        
                    $query = "INSERT INTO tamaños_x_guardianes(id_tamaño, id_guardian) 
                        VALUES ((select id_tamaño from tamaños where nombre_tamaño = :tamano), 
                        (select id_guardian from guardianes g inner join usuarios u on u.id_usuario = g.id_usuario where g.id_usuario= :id_usuario));";

                    $parametersTam["tamano"] = $tamaño;
                    $parametersTam["id_usuario"] = $_SESSION["UserId"];

                    $resultado=$this->connection->ExecuteNonQuery($query, $parametersTam);

                }
                return $resultado;

            }
            catch (Exception $ex) {

                //throw $ex;
                throw new Exception("Error en la base de datos. Intentelo más tarde");
            }

    }

    public function getGuardianesFiltradosFecha($fechaMin, $fechaMax){ //CHECKED

        $listaFiltrada = array();

        $query = "SELECT 
        g.id_usuario,
        u.username,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        u.foto_perfil,
        g.dia_inicio,
        g.dia_fin,
        g.descripcion,
        g.costo_diario,
        g.foto_espacio
        FROM
        usuarios u
        INNER JOIN
        guardianes g ON g.id_usuario = u.id_usuario
        where ((g.dia_inicio between :fecha_min and :fecha_max) 
		or (g.dia_fin between :fecha_min and :fecha_max)) 
        ;";

        $parameters["fecha_min"] = $fechaMin;
        $parameters["fecha_max"] = $fechaMax;

        
        $this->connection = Connection::GetInstance();


        try {

            $resultSet = $this->connection->Execute($query, $parameters);

            foreach($resultSet as $reg){

                $guardian=new Guardian();

                $guardian->setId($reg["id_usuario"]);
                $guardian->setUsername($reg["username"]);
                $guardian->setNombre($reg["nombre"]);
                $guardian->setApellido($reg["apellido"]);
                $guardian->setCorreoelectronico($reg["correo"]);
                $guardian->setTelefono($reg["telefono"]);
                $guardian->setFotoPerfil($reg["foto_perfil"]);
                $guardian->setFechaInicio($reg["dia_inicio"]);
                $guardian->setDescripcion($reg["descripcion"]);
                $guardian->setFechaFin($reg["dia_fin"]);
                $guardian->setCosto($reg["costo_diario"]);
                $guardian->setFotoEspacioURL($reg["foto_espacio"]);
                $guardian->setTipoMascota($this->obtenerTamañosMascotas($reg["id_usuario"]));

                array_push($listaFiltrada,$guardian);

            }

            return $listaFiltrada;

        } catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function getGuardianPorNombre($nombreGuardian){ //CHECKED

        $query = "SELECT 
        g.id_usuario,
        u.username,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        u.foto_perfil,
        g.dia_inicio,
        g.dia_fin,
        g.descripcion,
        g.costo_diario,
        g.foto_espacio
        FROM
        usuarios u
        INNER JOIN
        guardianes g ON g.id_usuario = u.id_usuario
        where u.username like '".$nombreGuardian."%';";

        $parameters["username"] = $nombreGuardian;

        
        $this->connection = Connection::GetInstance();

        $guardian = null;
        $listaGuardianes = array();

        try {

            $resultSet = $this->connection->Execute($query);

            //ACA SE UTILIZA LOGICA PORQUE PARA PODER SER MAPEADO EL GUARDIAN SE NECESITA CHECKEAR SI ME DEVUELVE ALGO LA QUERY
            //SI NO PONGO EL IF NO VA A SALTAR UN ERROR, POR ENDE NO LO AGARRA EL CATCH
            //SE VA A OCACIONAR UN WARNING. EJEMPLO: Warning: Trying to access array offset on value of type null in C:\xampp\htdocs\TP\PetHero\Framework\DAO\GuardianDAO.php on line 432
            
          
                foreach($resultSet as $reg){


                    $guardian=new Guardian();
                
                    $guardian->setId($reg["id_usuario"]);
                    $guardian->setUsername($reg["username"]);
                    $guardian->setNombre($reg["nombre"]);
                    $guardian->setApellido($reg["apellido"]);
                    $guardian->setCorreoelectronico($reg["correo"]);
                    $guardian->setTelefono($reg["telefono"]);
                    $guardian->setFotoPerfil($reg["foto_perfil"]);
                    $guardian->setFechaInicio($reg["dia_inicio"]);
                    $guardian->setDescripcion($reg["descripcion"]);
                    $guardian->setFechaFin($reg["dia_fin"]);
                    $guardian->setCosto($reg["costo_diario"]);
                    $guardian->setFotoEspacioURL($reg["foto_espacio"]);
                    $guardian->setTipoMascota($this->obtenerTamañosMascotas($reg["id_usuario"]));

                    array_push($listaGuardianes, $guardian);


                }

            return $listaGuardianes;
            
            /*

            if($resultSet){

                $reg = $resultSet[0];

                $guardian=new Guardian();
                
                $guardian->setId($reg["id_usuario"]);
                $guardian->setUsername($reg["username"]);
                $guardian->setNombre($reg["nombre"]);
                $guardian->setApellido($reg["apellido"]);
                $guardian->setCorreoelectronico($reg["correo"]);
                $guardian->setTelefono($reg["telefono"]);
                $guardian->setFotoPerfil($reg["foto_perfil"]);
                $guardian->setFechaInicio($reg["dia_inicio"]);
                $guardian->setDescripcion($reg["descripcion"]);
                $guardian->setFechaFin($reg["dia_fin"]);
                $guardian->setCosto($reg["costo_diario"]);
                $guardian->setFotoEspacioURL($reg["foto_espacio"]);
                $guardian->setTipoMascota($this->obtenerTamañosMascotas($reg["id_usuario"]));


            }

            return $guardian;


            */

        } catch (Exception $ex) {

            throw $ex;
            //throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }
}
