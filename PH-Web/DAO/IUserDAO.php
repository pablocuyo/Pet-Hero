<?php 

namespace DAO;
use Models\Usuario as Usuario;

interface IUserDAO{

    public function checkUsuario($username, $dni, $correo);
    public function Add(Usuario $user, $tipoUsuario);

}



?>