<?php 

namespace Models;

class Archivos{


    static public function subirArch($nombreArch, $arch, $path, $prefijo)
    {
        //var_dump($arch);
        if (isset($arch)) {
            //Recogemos el archivo enviado por el formulario
            $archivo = $prefijo."-". $_FILES[$nombreArch]['name'];
            //Si el archivo contiene algo y es diferente de vacio
            if (isset($archivo) && $archivo != "") {
                $tipo = $_FILES[$nombreArch]['type'];
                $tamano = $_FILES[$nombreArch]['size'];
                $temp = $_FILES[$nombreArch]['tmp_name'];

                //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
                if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 10000000000000))) {
                    echo "mal";
                } else {

                    //Si la imagen es correcta en tamaño y tipo
                    //Se intenta subir al servidor
                    if (move_uploaded_file($temp, UPLOADS_PATH .$path. $archivo)) {
                        //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                        chmod(  UPLOADS_PATH .$path. $archivo, 0777);

                    } else {
                        //Si no se ha podido subir la imagen, mostramos un mensaje de error
                        echo "mal";
                    }
                }
            }
        }
    }






}



?>