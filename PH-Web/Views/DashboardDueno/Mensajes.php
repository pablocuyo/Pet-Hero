<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/mensajes.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="cabecera">
        <div class="logo"><a href='<?php echo FRONT_ROOT . "Home/LogOut"?>'><img src="../assets/img/PetHeroLogo.png" height="100"></a>
        </div>
        <div><a href="<?php echo FRONT_ROOT . "Home/LogOut"?>">LOG OUT</a></div>
    </div>
    <div class="contenedora-general">
        <div class="contenedora-section">
            <div class="contendora-chat">
                <div class="lista-mensajes">
                    <?php
                    foreach($listaMensajes as $mensaje){
                        if($mensaje->getEmisor()==$_SESSION["UserId"]){
                            ?>
                            <div class="contenedora-mensaje derecha"><div class="mensaje der"><?php echo $mensaje->getContenido();?></div><div class="side-msj"><div class="autor-mensaje">Yo</div><div class="fecha"><?php echo $mensaje->getFecha();?></div></div></div>
                        
                    <?php    
                    }else{
                        ?>
                        <div class="contenedora-mensaje izquierda"><div class="side-msj"><div class="autor-mensaje"><?php echo $usuario->getUsername();?></div><div class="fecha"><?php echo $mensaje->getFecha();?></div></div><div class="mensaje interlocutor"><?php echo $mensaje->getContenido();?></div></div>
                    <?php
                    }
                }
                    ?>
                    
                    
                </div>
                <hr>
                
                
                <form class="contenedora-reply" action="../Mensaje/Add" method="post">
                    <input type="number" name="id" class="destinatario" value="<?php echo($usuario->getId());?>" readonly>
                    <textarea name="chat" class="reply" maxlength="150" placeholder="max 150 caracteres" size="50" required></textarea> 
                    <div class="Go send"><input type="submit" class="send"></div>
                </form>
            <hr>
            </div>
            
        </div>
        <aside>
            <?php require_once(VIEWS_PATH."DashboardDueno/MenuDash.php");?>
        </aside>
    </div>

    <div class="footer-separador"></div>
    <footer>
        <div>Copyright &#169 2022 Pet Hero S.A. es una empresa del grupo Batti's System CO.</div>
        <div><a href="">Terminos y Condiciones</a></div>
        <div><a href="">Aviso de privacidad</a></div>
    </footer>

</body>

</html>