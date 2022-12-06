<?php
namespace Models;

class Usuario {

    private $id;
    private $username;
    private $dni;
    private $nombre;
    private $apellido;
    private $correoelectronico;
    private $password;
    private $telefono;
    private $direccion;
    private $fotoPerfil;
    private $tipoUsuario;

    public function __construct(
        $id = null, 
        $username= null, 
        $dni = null, 
        $nombre = null, 
        $apellido= null,
        $correoelectronico = null,
        $password= null,
        $telefono= null,
        $direccion= null,
        $fotoPerfil= "perfil-default.png",
        $tipoUsuario = null
        )
    {
        
        $this->id= $id;
        $this->username= $username;
        $this->dni= $dni; 
        $this->nombre= $nombre;
        $this->apellido= $apellido;
        $this->correoelectronico= $correoelectronico;
        $this->password= $password;
        $this->telefono= $telefono;
        $this->direccion= $direccion;
        $this->fotoPerfil= $fotoPerfil;
        $this->tipoUsuario= $tipoUsuario;  
    }
    
    
    public function getUsername(){
        return $this->username;
    }
    public function getDni(){
        return $this->dni;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getCorreoelectronico(){
        return $this->correoelectronico;
    }
    public function getPassword(){
        return $this->password;
    }
    public function setUsername($username){
        $this->username=$username;
    }
    public function setDni($dni){
        $this->dni=$dni;
    }
    public function setNombre($nombre){
        $this->nombre=$nombre;
    }
    public function setApellido($apellido){
        $this->apellido=$apellido;
    }
    public function setCorreoelectronico($email){
        $this->correoelectronico=$email;
    }
    public function setPassword($pass){
        $this->password=$pass;
    }
     
    public function getTelefono()
    {
        return $this->telefono;
    }
 
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

    }
    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

    }

    public function setId($id)
    {
        $this->id = $id;

    }

    public function getId()
    {
        return $this->id;
    }

    public function getFotoPerfil(){

        return $this->fotoPerfil;
    }
   
    public function setFotoPerfil($urlFoto){

        $this->fotoPerfil = $urlFoto;
    }

    public function getTipoUsuario(){

        return $this->tipoUsuario;
    }

    public function setTipoUsuario($tipoUsuario){

        $this->tipoUsuario = $tipoUsuario;
    }

    
    
}
?>