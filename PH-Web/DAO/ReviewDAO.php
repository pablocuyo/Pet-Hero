<?php

namespace DAO;

use DAO\Connection;

use Exception;
use Models\Review;

class ReviewDAO{
    
    private $connection;

    public function crearReview(Review $review){

        $query = "INSERT INTO reviews(fecha, id_dueño, id_guardian, id_reserva, calificacion, comentario) 
        values (current_timestamp(), :id_dueno, :id_guardian, :id_reserva, :calificacion, :comentario);";

        $parameters["id_dueno"] = $_SESSION["UserId"];
        $parameters["id_guardian"] = $review->getGuardian();
        $parameters["id_reserva"] = $review->getReserva();
        $parameters["calificacion"] = $review->getCalificacion();
        $parameters["comentario"] = $review->getComentario();


        $this->connection = Connection::GetInstance();
         
        try{

            return $this->connection->ExecuteNonQuery($query, $parameters);

        }
        catch(Exception $ex){

            throw $ex;
            //throw new Exception("Error en la base de datos. Intentelo más tarde");

        }

    }

    public function devolverReviewPorReserva($idReserva){

        $review = null;

        $query = "SELECT * FROM reviews r where r.id_reserva = :id_reserva";

        $parameters["id_reserva"] = $idReserva;


        $this->connection = Connection::GetInstance();
         
        try{

            $resultSet = $this->connection->Execute($query, $parameters);

            if($resultSet){

                $reg = $resultSet[0];
                $review = new Review();

                $review->setId($reg["id_review"]);
                $review->setFecha($reg["fecha"]);
                $review->setDueño($reg["id_dueño"]);
                $review->setGuardian($reg["id_guardian"]);
                $review->setReserva($reg["id_reserva"]);
                $review->setCalificacion($reg["calificacion"]);
                $review->setComentario($reg["comentario"]);
            }

            return $review;

        }
        catch(Exception $ex){

            //throw $ex;
            throw new Exception("Error en la base de datos. Intentelo más tarde");

        }

    }

    public function devolverReviewsGuardian($idGuardian){

        $listaReviews = array();

        $query = "SELECT
        r.id_review,
        r.fecha,
        (select u.username from usuarios u where r.id_dueño = u.id_usuario) as dueño,
        r.id_guardian,
        r.id_reserva,
        r.calificacion,
        r.comentario
        FROM
        reviews r
        where r.id_guardian = :id_guardian
        order by r.id_reserva desc";

        $parameters["id_guardian"] = $idGuardian;

        $this->connection = Connection::GetInstance();
         
        try{

            $resultSet = $this->connection->Execute($query, $parameters);

            foreach($resultSet as $reg){

                $review = new Review();

                $review->setId($reg["id_review"]);
                $review->setFecha($reg["fecha"]);
                $review->setDueño($reg["dueño"]);
                $review->setGuardian($reg["id_guardian"]);
                $review->setReserva($reg["id_reserva"]);
                $review->setCalificacion($reg["calificacion"]);
                $review->setComentario($reg["comentario"]);

                array_push($listaReviews, $review);

            }

            return $listaReviews;

        }
        catch(Exception $ex){

            throw $ex;
            //throw new Exception("Error en la base de datos. Intentelo más tarde");

        }

    }

}

?>