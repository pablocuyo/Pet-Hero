<?php
namespace Controllers;


use DAO\DueñoDAO as DueñosDAO;
use DAO\GuardianDAO as GuardianDAO;
use DAO\MascotaDAO as MascotaDAO;
use DAO\ReservaDAO as ReservaDAO;
use DAO\UserDAO;
use DAO\ReviewDAO;
use Exception;
use Models\Reserva as Reserva;
use Models\Alert as Alert;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;



class ReservasController{
    
    private $ReservaDAO;
    private $GuardianDAO;
    private $MascotaDAO;
    private $DueñoDAO;
    private $UserDAO;


    public function __construct(){

        $this->ReservaDAO = new ReservaDAO();
        $this->GuardianDAO = new GuardianDAO();
        $this->MascotaDAO = new MascotaDAO();
        $this->DueñoDAO = new DueñosDAO();
        $this->UserDAO = new UserDAO();
    }

    public function Add(){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") { //CHECKED

            $reserva=unserialize($_SESSION["ReservaTemp"]);
            unset($_SESSION["ReservaTemp"]);
            $type = "danger";
          
            try{

                if($this->ReservaDAO->crearReserva($reserva)){

                    $type = "success";
                    throw new Exception("Solicitud enviada exitosamente");

                }else{

                    throw new Exception("No se pudo crear la reserva, intente nuevamente");

                }


            }catch(Exception $ex){

                $alert = new Alert($type, $ex->getMessage());
                $this->VerReservasDueno($alert);
            }

        }else{

            header("location: ../Home");
        }
    }

    public function VerReservasDueno($alert = null){ //CHECKED

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            try{

                $listaReservas = $this->ReservaDAO->listarReservasDueno();

                if($listaReservas){
                  
                    
                    require_once(VIEWS_PATH. "DashboardDueno/Reservas.php");

                }else{

                    throw new Exception("No hay reservas pendientes");

                }

                
            }catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                require_once(VIEWS_PATH. "DashboardDueno/Reservas.php");

            }     

        }else{

            header("location: ../Home");
        }
    }

    public function VerReservasGuardian($alert = null){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G") {

            try{

                $reservasAceptadas = $this->ReservaDAO->listarSolicitudesOrReservas("Aceptada");
                $reservasPagadas = $this->ReservaDAO->listarSolicitudesOrReservas("Pagada");
                $reservasAnuladas = $this->ReservaDAO->listarSolicitudesOrReservas("Anulada");
                $estadiasCompletadas = $this->ReservaDAO->ListarSolicitudesOrReservas("Completada");

                $listaReservas = array_merge($reservasAceptadas, $reservasPagadas,$reservasAnuladas, $estadiasCompletadas);

                if($listaReservas){

                    require_once(VIEWS_PATH. "DashboardGuardian/Reservas.php");

                }else{
            
                    throw new Exception("No posee reservas");
                }

            }
            catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                
                require_once(VIEWS_PATH. "DashboardGuardian/Reservas.php");
            }  
            
        }else{

            header("location: ../Home");
        }
    }

    public function VerSolicitudesGuardian($alert = null){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G") {

            try{

                $listaSolicitudes = $this->ReservaDAO->listarSolicitudesOrReservas("Pendiente");

                if($listaSolicitudes){

                    require_once(VIEWS_PATH. "DashboardGuardian/Solicitudes.php");

                }else{

                    throw new Exception("No tiene solicitudes pendientes");
                }

            }catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                require_once(VIEWS_PATH. "DashboardGuardian/Solicitudes.php");

            }

        }else{

            header("location: ../Home");
        }
    }

    public function CancelarSolicitud($idReserva){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            $type = "danger";

            try{

                $reserva = $this->ReservaDAO->devolverReservaPorId($idReserva);
                $estado = $reserva->getEstado();

                if($reserva){

                    switch($estado){

                        case "Aceptada":

                            $this->EnviarMailAnulacion($reserva);

                            if($this->ReservaDAO->cambiarEstadoReserva($idReserva, "Anulada")){
    
                                $type = "success";
                                throw new Exception("La reserva fue anulada con exito. Se le avisara al guardian");

                            }else{

                                throw new Exception("No se pudo cancelar la solicitud");
                            }

                            
                              
                        break;

                        case "Pendiente":

                            if($this->ReservaDAO->cancelarSolicitud($idReserva)){

                                $type = "success";
                                throw new Exception("La reserva fue cancelada");

                            }else{
                                
                                throw new Exception("No se pudo cancelar la solicitud");
                            }
                            
                        break;

                        case "Rechazado":

                            if($this->ReservaDAO->cancelarSolicitud($idReserva)){
    
                                throw new Exception("Reserva quitada del historial");

                            }else{

                                throw new Exception("No se pudo cancelar la solicitud");
                            }
                            
                        break;

                        case "Pagada":
                            throw new Exception("Solo se pueden quitar las reservas rechazadas o pendientes");  
                        break;

                        case "Completada":
                            throw new Exception("Solo se pueden quitar las reservas rechazadas o pendientes");  
                        break;

                    }
 
                }else{

                    throw new Exception("No se pudo encontrar la reserva");
                }

            }catch (Exception $ex){

                $alert = new Alert($type, $ex->getMessage());
                $this->VerReservasDueno($alert);
                     
            }

        }else{

            header("location: ../Home");
        }   


    }

    public function AceptarSolicitud($idReserva){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G") {
        
            $type = "danger";

            try{

                $reserva = $this->ReservaDAO->devolverReservaPorId($idReserva);
                $mascota = $this->MascotaDAO->devolverMascotaPorId($reserva->getMascota());

                if($reserva){

                    $checkTipoEstadia = $this->checkTipoEstadia($reserva, $mascota);

                    switch($checkTipoEstadia){
    
                        case 0:

                            if($this->ReservaDAO->cambiarEstadoReserva($idReserva, "Aceptada")){
        
                                $reserva = $this->ReservaDAO->devolverReservaPorId($idReserva);
                                   
                                $this->EnviarMailAceptacion($reserva);

                                $type = "success";
                                throw new Exception("La solicitud fue confirmada con exito");
        
                            }else{

                                throw new Exception("No se pudo aceptar la solicitud. Error de servidor");

                            }            
                        break;

                        case 3:

                            if($this->ReservaDAO->cambiarEstadoReserva($idReserva, "Aceptada")){
        
                                $reserva = $this->ReservaDAO->devolverReservaPorId($idReserva);
                                
                                $this->EnviarMailAceptacion($reserva);
                                
                                $type = "success";
                                throw new Exception("La solicitud fue confirmada con exito");

        
                            }else{

                                throw new Exception("No se pudo aceptar la solicitud. Error de servidor");

                            }            
                        break;
                        
                        case 1:          
                        throw new Exception("Raza incompatible con la que se cuida ese rango de fecha");
                        break;
    
                    }

                }else{

                    throw new Exception("No se pudo encontrar la reserva");
                }

                

            }catch(Exception $ex){

                $alert= new Alert($type, $ex->getMessage());
                $this->VerReservasGuardian($alert);

            }
        
        }else{

            header("location: ../Home");
        }  
    }

    public function RechazarSolicitud($idReserva){

        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G") {
              
            try{

                $reserva = $this->ReservaDAO->devolverReservaPorId($idReserva);

                if($reserva){

                    if($this->ReservaDAO->cambiarEstadoReserva($idReserva, "Rechazada")){

                        header("location: ../Reservas/VerSolicitudesGuardian");

                    }else{

                        throw new Exception("No se pudo rechazar la solicitud");
                    }
    
                    
                }else{

                    throw new Exception("Error al encontrar la reserva");
                }

            }catch(Exception $ex){

                header("location: ../Guardianes/VistaDashboard?alert=".$ex->getMessage()."&tipo=danger");

            }

        }else{

            header("location: ../Home");
        }

    }

    public function ValidarToken($tokenInput){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            $idReserva = $_SESSION["ReservaTemp"];
            unset($_SESSION["ReservaTemp"]);
            
            try{

                $token = $this->ReservaDAO->devolverTokenReserva($idReserva);

                if($tokenInput == $token){

                    header("location: ../Reservas/VistaPago?idReserva=".$idReserva."&tokenFlag=1");

                }else{

                    throw new Exception("Token Invalido. Revise que coincida con el enviado");

                }

            }
            catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->VerReservasDueno($alert);

            }



        }else{

            header("location: ../Home");
        } 


    }

    public function Iniciar($idGuardian, $alert=null){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {
            

            try{

                $guardian=$this->GuardianDAO->devolverGuardianPorId($idGuardian);              
                $listaMascotas = $this->MascotaDAO->GetAll();
    
                if($guardian){

                    if($listaMascotas){
    
                        $_SESSION["GuardianId"] = $guardian->getId();
        
                        //Guardo el id en sesion para llevarlo al metodo confirmar y mantener el usuario elegido
                        require_once(VIEWS_PATH. "DashboardDueno/Solicitud.php");
        
                    }else{
        
                        header("location: ../Mascotas/VerFiltroMascotas?alert=Registre una mascota para solicitar el cuidado de un guardian");
                    }

                }else{

                    throw new Exception("No se pudo inicar la reserva. Error al encontrar el Guardian");

                }

            }
            catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->VerReservasDueno($alert);

            }
   
        }else{

            header("location: ../Home");
        } 
    }

    public function Confirmar($fechaIn,$fechaOut,$idMascota){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            try{

                $guardian = $this->GuardianDAO->devolverGuardianPorId($_SESSION["GuardianId"]);

                unset($_SESSION["GuardianId"]);

                $mascota = $this->MascotaDAO->devolverMascotaPorId($idMascota);

                $reserva = new Reserva();
        
                $reserva->setFecha(date("Y-m-d H:i:s"));
                $reserva->setFechaInicio($fechaIn);
                $reserva->setFechaFin($fechaOut);
                $reserva->setMascota($mascota->getId());
                $reserva->setGuardian($guardian->getId());
                $reserva->setDueño($_SESSION["UserId"]);
                $costo = $guardian->getCosto() * $this->calcularFecha($fechaIn,$fechaOut);
                $reserva->setCosto($costo);
                $reserva->setEstado("Pendiente");

                switch($this->CheckSolicitud($guardian, $mascota, $reserva)){


                    case 0:

                        throw new Exception("El tamaño de su mascota no coincide con el que cuida el guardian");
                        break;

                    case 1:

                        throw new Exception("La fecha de fin tiene que ser mayor a la de inicio");
                        break;
                
                    case 2:
                        $_SESSION["ReservaTemp"] = serialize($reserva);   
                        require_once(VIEWS_PATH. "DashboardDueno/ConfirmarSolicitud.php");
                    break;
    
                }

            }catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->Iniciar($guardian->getId(), $alert);

            }
            
        }else{

            header("location: ../Home");
        }
    }

    public function VerAuthPago($idReserva){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            try{

                $reserva=$this->ReservaDAO->devolverReservaPorId($idReserva);
                
                if($reserva){
    
                    switch($reserva->getEstado()){

                        case "Aceptada":
                            $_SESSION["ReservaTemp"] = $idReserva;
                            require_once(VIEWS_PATH."DashboardDueno/AutentificarPago.php");
                        break;

                        case "Pendiente":
                            throw new Exception("La reserva tiene que ser aceptada para poder pagarse");
                        break;

                        case "Pagada":
                            throw new Exception("La reserva ya esta pagada");
                        break;

                        case "Rechazada":
                            throw new Exception("La reserva no puede pagarse si esta rechazada");
                        break;

                        case "Completada":
                            throw new Exception("No se puede pagar una reserva completada");
                        break;
                    }


    
                }else{
    
                    throw new Exception("Error al procesar el pago de la reserva");
                }

                
            }catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->VerReservasDueno($alert);
            }
                  
        }else{

            header("location: ../Home");
        }   

    }

    public function VistaPago($idReserva=null, $tokenFlag=null){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

                
            try{

                if($tokenFlag){
                    
                    $reserva = $this->ReservaDAO->DevolverReservaPorId($idReserva);
                    $guardian = $this->GuardianDAO->devolverGuardianPorId($reserva->getGuardian());
                    $dueño = $this->DueñoDAO->devolverDueñoPorId($reserva->getDueño());
                    $mascota = $this->MascotaDAO->devolverMascotaPorId($reserva->getMascota());

                    require_once(VIEWS_PATH."DashboardDueno/PagoReserva.php");
                         
                }else{

                    throw new Exception("No puede acceder a esta pagina sin un Token");
                
                }
                
            }catch(Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->VerReservasDueno($alert);
                
            }

        }else{

            header("location: ../Home");
        }   
         
    }
    
    public function PagarReserva($tc, $numeroTarjeta, $nombre, $apellido, $vencimiento, $cvc, $idReserva){

        // Se tendria que implementar una verificacion de tarjeta
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $type = "danger";
            try{

                $id = (int) $idReserva;

                $this->ReservaDAO->eliminarTokenReserva($idReserva);
              
                $this->ReservaDAO->cambiarEstadoReserva($idReserva, "Pagada");

                $this->EnviarMailFactura($this->ReservaDAO->devolverReservaPorId($id));

                $type= "success";
                throw new Exception("Reserva pagada con exito");



            }catch(Exception $ex){

                $alert = new Alert($type, $ex->getMessage());
                $this->VerReservasDueno($alert);

            }


        }else{

            header("location: ../Home");
        }
        


        
    }

    public function CompletarCuidado($idReserva){

        // Se tendria que implementar una verificacion de tarjeta
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            $type = "danger";

            try{

                $id = (int) $idReserva;

                $reserva = $this->ReservaDAO->DevolverReservaPorId($id);
                
                if($reserva){

                    switch($reserva->getEstado()){

                        case "Aceptada":
                            throw new Exception("La reserva no esta pagada");
                        break;

                        case "Pagada":
                            //Aqui tendria que ir un algoritmo que checkee si la fecha fin es menor a la actual y si el cuidado se efetuó correctamente
                            $type = "success";
                            $this->ReservaDAO->cambiarEstadoReserva($id, "Completada");
                            throw new Exception("Se completo la reserva. Gracias por su servicio");
                        break;

                        case "Anulada":
                            throw new Exception("La reserva nunca se hizo");
                        break;

                        case "Completada":
                            throw new Exception("La reserva ya aparece como completada");
                        break;

                    }

                }else{

                    throw new Exception("Error al procesar la reserva");
                }


            }catch(Exception $ex){

                $alert = new Alert($type, $ex->getMessage());
                $this->VerReservasGuardian($alert);

            }


        }else{

            header("location: ../Home");
        }
    }

    public function VistaGenerarReview($idReserva){


        if (isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D") {

            $ReservaDAO = new ReservaDAO();
            $GuardianDAO = new GuardianDAO();
            $MascotaDAO = new MascotaDAO();
            $ReviewDAO = new ReviewDAO();
        
            try{
                
                
                $reserva = $ReservaDAO->DevolverReservaPorId($idReserva);
                
                if($reserva){

                    $guardian = $GuardianDAO->devolverGuardianPorId($reserva->getGuardian());
                    $mascota = $MascotaDAO->devolverMascotaPorId($reserva->getMascota());
                    $review = $ReviewDAO->devolverReviewPorReserva($reserva->getId());

                    if($reserva->getEstado() == "Completada"){

                        if($review){

                            throw new Exception("Usted ya realizo una review sobre esa estadía"); 
    
                        }else{
                            
                            require_once(VIEWS_PATH. "DashboardDueno/Calificar.php");
                        }

                    }else{

                        throw new Exception("La estadía tiene que estar completada para generar una review"); 
                    }


    
                }else{

                    throw new Exception("Error al procesar la review");
                }

                    
      

            }catch (Exception $ex){

                $alert = new Alert("danger", $ex->getMessage());
                $this->VerReservasDueno($alert);
            }

        }else{

            header("location: ../Home");
        }
        
    }

    private function CheckTipoEstadia($reserva, $mascota){


        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            $resultado = 0;

            try{

                $listaReservas = $this->ReservaDAO->devolverReservasEnRango($reserva->getFechaInicio(), $reserva->getFechaFin());
                //Recorro todas las reservas que estan entre la fecha de inicio y final de la que se quiere aceptar

                if($listaReservas){

                    foreach($listaReservas as $reservaEnRango){
                        //Obtengo la mascota de cada reserva dentro del rango
                        $mascotaTemp = $this->MascotaDAO->devolverMascotaPorId($reservaEnRango->getMascota());
    
    
                        if($mascota->getRaza() != $mascotaTemp->getRaza()){
    
                            $resultado = 1;
                        }
                    }


                }else{

                    $resultado = 3;

                }  
                
                return $resultado;

            }catch(Exception $ex){

                throw $ex;
            }

        }else{

            header("location: ../Home");
        }
        
    }

    private function CheckSolicitud($guardian, $mascota, $reserva){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $resultado = 0;

            if($reserva->getFechaFin() < $reserva->getFechaInicio()){

                $resultado = 1;
                
            }else{

                foreach($guardian->getTipoMascota() as $tamaño){

                    if($mascota->getTamaño() == $tamaño){
        
                        $resultado = 2;
                    }
                }

            }

            return $resultado;

        }else{

            header("location: ../Home");
        }

    }

    private function CalcularFecha($fechaIn,$fechaOut){
        //0 indice años, 1 meses, 2 dias, 11 total de dias.
        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $fecha1=date_create($fechaIn);
            $fecha2=date_create($fechaOut);    
            $intervalo=date_diff($fecha1,$fecha2);
            $tiempo=array();
            foreach($intervalo as $medida){
                $tiempo[]=$medida;

            }
            return $tiempo[11];

        }else{

            header("location: ../Home");
        }

    }
         
    private function EnviarMailAceptacion(Reserva $reserva){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            $mail = new PHPMailer(True);
        
            try{

                $dueño = $this->DueñoDAO->devolverDueñoPorId($reserva->getDueño());
                $guardian = $this->GuardianDAO->devolverGuardianPorId($reserva->getGuardian());
                $mascota = $this->MascotaDAO->devolverMascotaPorId($reserva->getMascota());
                $token = $this->GenerarToken();

                $mailReceptor = $dueño->getCorreoelectronico();

                //Server settings
                //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                   // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'petherolab@gmail.com';                 // SMTP username
                $mail->Password = 'yowqxocqmbfexmvd';                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('petherolab@gmail.com', 'Mailer');
                $mail->addAddress($mailReceptor, 'Mailer');  

                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');


                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Solicitud aceptada por el Guardian '. $guardian->getUsername();

                $mail->Body = "Hola <b>".$dueño->getUsername()."</b>!<br><br>Su reserva con el guardian <b>" . $guardian->getUsername() . "</b> a sido aceptada para el dia <b>". $reserva->getFechaInicio() . "</b> con finalizacion en <b>" . $reserva->getFechaFin() . "</b>.<br>La mascota a cuidar es <b>" . $mascota->getNombre() . "</b> con un importe de <b>$" . $reserva->getCosto() . "</b>. El importe a pagar en primera estancia es el 50%. <br><br>Su Token de confirmación es: <b>". $token ."</b>";

                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                $this->ReservaDAO->subirTokenReserva($token, $reserva->getId());

            }catch(Exception $ex){

                throw $ex;
            }

        }else{

            header("location: ../Home");
        }
        
    }

    private function EnviarMailFactura(Reserva $reserva){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $mail = new PHPMailer(True);
        
            try{

                $dueño = $this->DueñoDAO->devolverDueñoPorId($reserva->getDueño());
                $guardian = $this->GuardianDAO->devolverGuardianPorId($reserva->getGuardian());

                $mailReceptor = $dueño->getCorreoelectronico();

                //Server settings
                //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                   // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'petherolab@gmail.com';                 // SMTP username
                $mail->Password = 'yowqxocqmbfexmvd';                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('petherolab@gmail.com', 'Mailer');
                $mail->addAddress($mailReceptor, 'Mailer');  

                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');


                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Pago con exito de la reserva numero '. $reserva->getId();

                $mail->Body = "Hola <b>".$dueño->getUsername()."</b>!<br><br>Su reserva con el guardian <b>" . $guardian->getUsername() . "</b> a sido pagada exitosamente.<br><br>Debe llevar su mascota el dia <b>" .$reserva->getFechaInicio(). "</b> a la siguiente direccion: <b>". $guardian->getDireccion(). "</b>.";

                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();

            }catch(Exception $ex){

                throw $ex;
            }

        }else{

            header("location: ../Home");
        }

    }

    private function EnviarMailAnulacion(Reserva $reserva){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "D"){

            $mail = new PHPMailer(True);
        
            try{

                $dueño = $this->DueñoDAO->devolverDueñoPorId($reserva->getDueño());
                $guardian = $this->GuardianDAO->devolverGuardianPorId($reserva->getGuardian());
                $mascota = $this->MascotaDAO->devolverMascotaPorId($reserva->getMascota());

                $mailReceptor = $guardian->getCorreoelectronico();

                //Server settings
                //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                   // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'petherolab@gmail.com';                 // SMTP username
                $mail->Password = 'yowqxocqmbfexmvd';                           // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom('petherolab@gmail.com', 'Mailer');
                $mail->addAddress($mailReceptor, 'Mailer');  

                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');


                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Anulacion de reserva';

                $mail->Body = "Hola <b>".$guardian->getUsername()."</b>!<br><br>Lamentamos comunicarle que el dia <b>".$reserva->getFechaInicio()."</b> la mascota <b>" .$mascota->getNombre(). "</b> ya no va a contar con su cuidado. El dueño <b>".$dueño->getUsername()."</b> anuló la reserva.";

                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();

            }catch(Exception $ex){

                throw $ex;
            }

        }else{

            header("location: ../Home");
        }

    }

    private function GenerarToken(){

        if(isset($_SESSION["UserId"]) and $_SESSION["Tipo"] == "G"){

            $cadena = "QWERTYUIOPASDFGHJKLNZXCVBNM0123456789";
            $token = "";

            for ($i=0; $i < 10; $i++) { 
                
                $token.= $cadena[rand(0,35)];
            }


            return $token;

        }else{

            header("location: ../Home");
        }

    }
}