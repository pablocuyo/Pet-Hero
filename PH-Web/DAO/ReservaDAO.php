<?php 
namespace DAO;

use Exception;

use Models\Reserva as Reserva;
class ReservaDAO{

    private $connection;

    public function CrearReserva(Reserva $reserva){

        $query = "INSERT INTO reservas(fecha_reserva,fecha_inicio,fecha_fin, id_guardian,id_dueño,id_mascota, costo_total, estado) 
        values (:fechaReserva, :fechaInicio, :fechaFin,:idUserGuardian,:idUserDueno,:idMascota,:costoTotal,:estado);";


        $parameters["fechaReserva"] = $reserva->getFecha();
        $parameters["fechaInicio"] = $reserva->getFechaInicio();
        $parameters["fechaFin"] = $reserva->getFechaFin();
        $parameters["idUserGuardian"] = $reserva->getGuardian();
        $parameters["idUserDueno"] = $_SESSION["UserId"];
        $parameters["idMascota"] = $reserva->getMascota();
        $parameters["costoTotal"] = $reserva->getCosto();
        $parameters["estado"] = $reserva->getEstado();

        $this->connection = Connection::GetInstance();
        
        
        try{

            return $this->connection->ExecuteNonQuery($query, $parameters);

        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");

        }
    }

    public function cancelarSolicitud($idReserva){

        $query = "DELETE FROM reservas where id_reserva = :id_reserva;";

        $paramaters["id_reserva"] = $idReserva;

        $this->connection = Connection::GetInstance();

        try{

            return $this->connection->ExecuteNonQuery($query, $paramaters);

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }

    }

    public function ListarSolicitudesOrReservas($estado){ //Devuelve solicitudes o reservas del guardian dependendiendo del estado

        $listaReservas = array();

        $query = "CALL listar_solicitud_reservas(:estado, :id_usuario);";

        $paramaters["estado"] = $estado;
        $paramaters["id_usuario"] = $_SESSION["UserId"];

        $this->connection = Connection::GetInstance();

        try{
                
            $resultSet = $this->connection->Execute($query, $paramaters);

            foreach($resultSet as $reg){

                $reserva = new Reserva();

                $reserva->setId($reg["id_reserva"]);
                $reserva->setFecha($reg["fecha_reserva"]);
                $reserva->setFechaInicio($reg["fecha_inicio"]);
                $reserva->setFechaFin($reg["fecha_fin"]);
                $reserva->setGuardian($_SESSION["UserId"]);
                $reserva->setDueño($reg["dueño"]);
                $reserva->setMascota($reg["mascota"]);
                $reserva->setCosto($reg["costo_total"]);
                $reserva->setEstado($reg["estado"]);

                array_push($listaReservas, $reserva);
            }

            return $listaReservas;


        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }


    }

    public function cambiarEstadoReserva($idReserva, $estado){

        
        $query = "UPDATE reservas set estado= :estado where id_reserva = :id_reserva;";

        $paramaters["estado"] = $estado;
        $paramaters["id_reserva"] = $idReserva;

        $this->connection = Connection::GetInstance();

        try{

            return $this->connection->ExecuteNonQuery($query, $paramaters);

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function listarReservasDueno(){ // GUARDAR NOMBRES EN VEZ DE OBJETO

        $listaReservas = array();

        $query = "SELECT
        r.id_reserva,
        r.fecha_reserva,
        r.fecha_inicio,
        r.fecha_fin,
        u.username as guardian,
        r.id_guardian,
        m.nombre as mascota,
        r.costo_total,
        r.estado 
        from 
        reservas r
        inner join usuarios u on u.id_usuario = r.id_guardian
        inner join mascotas m on m.id_mascota = r.id_mascota
        where r.id_dueño = :id_usuario and r.estado != 'Anulada'";

        $parameters["id_usuario"] = $_SESSION["UserId"];

        $this->connection = Connection::GetInstance();
        
        try{

            $resultSet = $this->connection->Execute($query, $parameters);
    
            foreach($resultSet as $reg){
    
                $reserva = new Reserva();

                $reserva->setId($reg["id_reserva"]);
                $reserva->setFecha($reg["fecha_reserva"]);
                $reserva->setFechaInicio($reg["fecha_inicio"]);
                $reserva->setFechaFin($reg["fecha_fin"]);
                $reserva->setGuardian($reg["guardian"]);
                $reserva->setDueño($_SESSION["UserId"]);
                $reserva->setMascota($reg["mascota"]);
                $reserva->setCosto($reg["costo_total"]);
                $reserva->setEstado($reg["estado"]);

                array_push($listaReservas, $reserva);
            }

            return $listaReservas;

        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }


    }

    public function DevolverReservaPorId($idReserva){

        $query = "SELECT
        *
        from 
        reservas 
        where id_reserva = :id_reserva;";

        $parameters["id_reserva"] = $idReserva;

        $this->connection = Connection::GetInstance();

        try{
    
            $resultSet = $this->connection->Execute($query, $parameters);

            $reserva = null;

            if($resultSet){

                $reg = $resultSet[0];
                $reserva = new Reserva();
    
                $reserva->setId($reg["id_reserva"]);
                $reserva->setFecha($reg["fecha_reserva"]);
                $reserva->setFechaInicio($reg["fecha_inicio"]);
                $reserva->setFechaFin($reg["fecha_fin"]);
                $reserva->setGuardian($reg["id_guardian"]);
                $reserva->setDueño($reg["id_dueño"]);
                $reserva->setMascota($reg["id_mascota"]);
                $reserva->setCosto($reg["costo_total"]);
                $reserva->setEstado($reg["estado"]);

            }
  
            return $reserva;

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }
    }

    public function DevolverReservasEnRango($fechaMin, $fechaMax){

        $listaReservas = array();

        $query="select * from reservas r
        where ((r.fecha_inicio between :fecha_min and :fecha_max) 
        or (r.fecha_fin between :fecha_min and :fecha_max)) 
        and r.id_guardian = :id_usuario
        and r.estado = 'Aceptada';";

        $parameters["fecha_min"] = $fechaMin;
        $parameters["fecha_max"] = $fechaMax;
        $parameters["id_usuario"] = $_SESSION["UserId"];

        $this->connection = Connection::GetInstance();

        try{
    
            $resultSet = $this->connection->Execute($query, $parameters);

            foreach($resultSet as $reg){

                $reserva = new Reserva();
    
                $reserva->setId($reg["id_reserva"]);
                $reserva->setFecha($reg["fecha_reserva"]);
                $reserva->setFechaInicio($reg["fecha_inicio"]);
                $reserva->setFechaFin($reg["fecha_fin"]);
                $reserva->setGuardian($reg["id_guardian"]);
                $reserva->setDueño($reg["id_dueño"]);
                $reserva->setMascota($reg["id_mascota"]);
                $reserva->setCosto($reg["costo_total"]);
                $reserva->setEstado($reg["estado"]);

                array_push($listaReservas, $reserva);

            }
  
            return $listaReservas;

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }  
    }

    public function subirTokenReserva($tokenCheck, $idReserva){

        $query="insert into tokens (token, id_reserva) values (:token, :id_reserva)";

        $parameters["token"] = $tokenCheck;
        $parameters["id_reserva"] = $idReserva;
        
        $this->connection = Connection::GetInstance();
    
        try{
    
            return $this->connection->ExecuteNonQuery($query, $parameters);

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        } 

    }

    public function eliminarTokenReserva($idReserva){

        $query="delete from tokens where id_reserva = :id_reserva";

        $parameters["id_reserva"] = $idReserva;
        
        $this->connection = Connection::GetInstance();
    
        try{
    
            return $this->connection->ExecuteNonQuery($query, $parameters);

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        } 

    }

    public function devolverTokenReserva($idReserva){

        $token = null;

        $query="select t.token from tokens t where t.id_reserva= :id_reserva;";

        $parameters["id_reserva"] = $idReserva;
        
        $this->connection = Connection::GetInstance();
    
        try{
    
            $resultSet = $this->connection->Execute($query, $parameters);

            if($resultSet){

                $reg = $resultSet[0];
                $token = $reg["token"];
            }
  
            return $token;

        }catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        } 

    }

    public function listarCompletadasDueno($idGuardian){ // GUARDAR NOMBRES EN VEZ DE OBJETO

        $listaReservas = array();

        $query = "SELECT
        r.id_reserva,
        r.fecha_reserva,
        r.fecha_inicio,
        r.fecha_fin,
        u.username as guardian,
        r.id_guardian,
        m.nombre as mascota,
        r.costo_total,
        r.estado 
        from 
        reservas r
        inner join usuarios u on u.id_usuario = r.id_guardian
        inner join mascotas m on m.id_mascota = r.id_mascota
        where r.id_dueño = :id_usuario and r.id_guardian = :id_guardian and r.estado = 'Completada';";

        $parameters["id_guardian"] = $idGuardian;
        $parameters["id_usuario"] = $_SESSION["UserId"];

        $this->connection = Connection::GetInstance();
        
        try{

            $resultSet = $this->connection->Execute($query, $parameters);
    
            foreach($resultSet as $reg){
    
                $reserva = new Reserva();

                $reserva->setId($reg["id_reserva"]);
                $reserva->setFecha($reg["fecha_reserva"]);
                $reserva->setFechaInicio($reg["fecha_inicio"]);
                $reserva->setFechaFin($reg["fecha_fin"]);
                $reserva->setGuardian($reg["guardian"]);
                $reserva->setDueño($_SESSION["UserId"]);
                $reserva->setMascota($reg["mascota"]);
                $reserva->setCosto($reg["costo_total"]);
                $reserva->setEstado($reg["estado"]);

                array_push($listaReservas, $reserva);
            }

            return $listaReservas;

        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");
        }


    }

}