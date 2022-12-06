<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud</title>

    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/solicitud.css" rel="stylesheet">
    <link href="../styles/alert.css" rel="stylesheet">

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
            <div class="contenedora-solicitud">
                <div class="contenedora-cabecera">
                    <div class="img-perfil"><figure ><img class="foto-perfil" src="../assets/FotosUsuarios/<?php echo $guardian->getFotoPerfil()?>"></figure></div>
                    <div class="nombre-perfil"><?php echo $guardian->getUsername() ?></div>
                </div>
                <div class="contenedora-fechas">
                    <form action="<?php echo FRONT_ROOT ?>Reservas/Confirmar" method="POST">
                        <div class="contenedora-inputs">
                            <div class="cont">
                                <div><label for="fechaIn">Fecha Inicio</label></div>
                                <div></div><input type="date" name="fechaIn" min="<?php echo date($guardian->getFechaInicio()); ?>" max="<?php echo date($guardian->getFechaFin()); ?>">
                            </div>
                            <div class="cont">
                                <div><label for="fechaOut">Fecha Fin</label></div>
                                <div><input type="date" name="fechaOut" min="<?php echo date($guardian->getFechaInicio()); ?>" max="<?php echo date($guardian->getFechaFin()); ?>"></div>
                            </div>
                            <div class="cont">

                                <div><label for="Mascota">Mascota</label></div>

                                <div><select name="idMascota">

                                        <?php foreach ($listaMascotas as $mascota) { ?>

                                            <option value="<?php echo $mascota->getId() ?>"><?php echo $mascota->getNombre(); ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="cont">
                                <div class="boton">
                                    <button type="submit" class="submit"><a href=""><img src="../assets/img/choque.png"></a></button>
                                </div>
                            </div>
                        </div>


                    </form>
                    <?php if (isset($alert)) { ?>
                        <div class="alert-<?php echo $alert->getType() ?>"><?php echo $alert->getMessage() ?></div>
                    <?php } ?>
                </div>
            </div>

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