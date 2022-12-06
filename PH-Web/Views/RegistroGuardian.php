<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Guardian</title>
    <link href="../styles/regGuardian.css" rel="stylesheet" >
    <link href="../styles/alert.css" rel="stylesheet" >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
    </head>
  <body>
        <div class="cabecera">
                <div class="logo"><a href='../index.php'><img src="../assets/img/PetHeroLogo.png" height="100"></a></div>
                <div class="guardiantitulo"></div>
                <div><a href="<?php echo FRONT_ROOT . "Home/LogOut"?>">LOG OUT</a></div>
        </div>
        <div class="contenedora-registro">
        
        <form action="<?php echo FRONT_ROOT ?>Guardianes/Registro" method="post" enctype="multipart/form-data">
                <div class="contenedora-form">
                <div class="datoregistro">
                        <label for="usuario">Nombre de Usuario</label>
                        <input type="text" placeholder="" name="username" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="nombre">Nombre</label>
                        <input type="text" placeholder="" name="nombre" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="apellido">Apellido</label>
                        <input type="text" placeholder="" name="apellido" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="dni">DNI</label>
                        <input type="text" placeholder="" name="dni" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="mail">E-Mail</label>
                        <input type="email" placeholder="" name="mail" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="telefono">Telefono</label>
                        <input type="text" placeholder="(sin guiones)" name="telefono" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="direccion">Direccion</label>
                        <input type="text" placeholder="Direccion" name="direccion" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="pass">Contraseña</label>
                        <input type="password" placeholder="" name="password" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="re-pass">Repetir Contraseña</label>
                        <input type="password" placeholder="" name="rePassword" class="" required><br>
                </div>
                <div class="datoregistro">
                        <label for="fotoPerfil">Foto Perfil</label>
                        <input type="file" name="fotoPerfil" class=""><br>
                </div>
                        <div class="boton">
                                <button type="submit" class="submit"><a href=""><img src="../assets/img/choque.png"></a></button>
                         </div>
                </div>
                
                <?php if (isset($alert)) { ?>
                        <div class="alert-<?php echo $alert->getType()?>"><?php echo $alert->getMessage()?></div>
                <?php } ?>

        </form>

</div>
<div class="footer-separador"></div>
    <footer>
        <div>Copyright &#169 2022 Pet Hero S.A. es una empresa del grupo Batti's System CO.</div>
        <div><a href="">Terminos y Condiciones</a></div>
        <div><a href="">Aviso de privacidad</a></div>
    </footer>
        
</body>
</html>