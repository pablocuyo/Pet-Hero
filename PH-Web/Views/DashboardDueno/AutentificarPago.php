<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Elegir Mascota</title>

    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/auth.css" rel="stylesheet">
    <link href="../styles/alert.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">


<body>
    <div class="cabecera">
        <div class="logo"><a href='<?php echo FRONT_ROOT . "Home/LogOut"?>'><img src="../assets/img/PetHeroLogo.png" height="100"></a>
        </div>
        <div><a href="<?php echo FRONT_ROOT . "Home/LogOut" ?>">LOG OUT</a></div>
    </div>
    <div class="contenedora-general">
        <div class="contenedora-section">
            <div class="contenedora-token">
                <div>
                    <h2>Ingrese el Token enviado por mail</h2>
                </div>
                <div>
                    <form action="<?php echo FRONT_ROOT . "Reservas/ValidarToken"?>" method="POST">
                        <input class="input-token" type="text" name="tokenInput" value="">
                        <div >
                            <button class="boton-auth" type="submit">Autentificar</button>  
                        </div>  
                    </form>    
                </div>

            </div>
            <?php if (isset($alert)) { ?>
                <div class="alert-danger"><?php echo $alert ?></div>
            <?php } ?>
        </div>
        <aside>
            <?php require_once(VIEWS_PATH . "dashboardDueno/MenuDash.php"); ?>
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