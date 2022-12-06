<?php
namespace DAO;

use DAO\Connection;
use Models\Mascota;
use DAO\IDueñoDAO as IDueñoDAO;
use DAO\UserDAO as UserDAO;
use Exception;

class MascotaDAO implements InterfaceDAO{
    
    private $connection;

    public function GetAll(){ //CHECKED

        $listaMascotas = array();

        $query = "SELECT 
        m.id_mascota,
        m.nombre,
        r.nombre_raza,
        e.nombre_especie,
        t.nombre_tamaño,
        m.plan_vacunacion,
        m.foto_mascota,
        m.video
        FROM mascotas m
        inner join 
        tamaños t on t.id_tamaño = m.id_tamaño
        inner join
        razas r on r.id_raza = m.id_raza
        inner join 
        especies e on e.id_especie=r.id_especie
        where id_dueño = (select id_dueño from dueños d inner join usuarios u on u.id_usuario = d.id_usuario where u.id_usuario = :id_usuario);";
    
        $parameters["id_usuario"] = $_SESSION["UserId"];

        $this->connection = Connection::GetInstance();
        
        try{

            $resultSet = $this->connection->Execute($query, $parameters);

            foreach($resultSet as $reg){

                $mascota = new Mascota();

                $mascota->setId($reg["id_mascota"]);
                $mascota->setNombre($reg["nombre"]);
                $mascota->setRaza($reg["nombre_raza"]);
                $mascota->setEspecie($reg["nombre_especie"]);
                $mascota->setTamaño($reg["nombre_tamaño"]);
                $mascota->setPlanVacURL($reg["plan_vacunacion"]);
                $mascota->setFotoURL($reg["foto_mascota"]);
                $mascota->setVideoURL($reg["video"]);
                
                array_push($listaMascotas, $mascota);


            }

            return $listaMascotas;

        }catch (Exception $ex) {

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
        
    }
    
    public function devolverMascotaPorId($idMascota){

        $mascota = new Mascota();
   
        $query = "SELECT 
        m.id_mascota,
        m.nombre,
        r.nombre_raza,
        e.nombre_especie,
        t.nombre_tamaño,
        m.plan_vacunacion,
        m.foto_mascota,
        m.video
        FROM mascotas m
        inner join 
        tamaños t on t.id_tamaño = m.id_tamaño
        inner join
        razas r on r.id_raza = m.id_raza
        inner join 
        especies e on e.id_especie=r.id_especie
        where m.id_mascota = :id_mascota ;";

        $parameters["id_mascota"]= $idMascota;

        $this->connection = Connection::GetInstance();

        try{

            $resultSet = $this->connection->Execute($query, $parameters);

            if($resultSet){

                $reg=$resultSet[0];

                $mascota->setId($reg["id_mascota"]);
                $mascota->setNombre($reg["nombre"]);
                $mascota->setRaza($reg["nombre_raza"]);
                $mascota->setEspecie($reg["nombre_especie"]);
                $mascota->setTamaño($reg["nombre_tamaño"]);
                $mascota->setPlanVacURL($reg["plan_vacunacion"]);
                $mascota->setFotoURL($reg["foto_mascota"]);
                $mascota->setVideoURL($reg["video"]);  
            
            }
                
           return $mascota;

        }catch(Exception $ex) {

            throw $ex;

        }

    }
    
    public function Add(Mascota $mascota){ //CHECKED

        $query = "CALL agregar_mascota(:nombre, :raza, :tamano, :idUsuario, :planVacunacion, :foto, :video)";

        $parameters["nombre"] = $mascota->getNombre();
        $parameters["raza"] = $mascota->getRaza();
        $parameters["tamano"] = $mascota->getTamaño();
        $parameters["idUsuario"] = $_SESSION["UserId"];
        $parameters["planVacunacion"] = $mascota->getPlanVacURL();
        $parameters["foto"] = $mascota->getFotoURL();
        $parameters["video"] = $mascota->getVideoURL();

        $this->connection = Connection::GetInstance();
        
        try {

            return $this->connection->ExecuteNonQuery($query, $parameters);
          
        } catch (Exception $ex) {
            
            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    }

    public function listarRazas($especie){ //CHECKED

        $listaRazas = array();

        $query = "SELECT 
        r.nombre_raza
        FROM
        razas r
        INNER JOIN
        especies e ON r.id_especie = e.id_especie
        where e.nombre_especie = :especie ;";

        $parameters["especie"] = $especie;

        $this->connection = Connection::GetInstance();
        
        try{

            $resultSet = $this->connection->Execute($query, $parameters);
            
            foreach($resultSet as $reg){

                array_push($listaRazas, $reg["nombre_raza"]);

            }

            return $listaRazas;

        }
        catch(Exception $ex){

            //throw new $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function borrarMascota($id){ //CHECKED

        $query= "DELETE from mascotas where id_mascota=:idbuscado;";
        
        $parameters["idbuscado"]=$id;

        $this->connection = Connection::GetInstance();

        try{

            return $this->connection->ExecuteNonQuery($query, $parameters);

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        
        }

    }




}