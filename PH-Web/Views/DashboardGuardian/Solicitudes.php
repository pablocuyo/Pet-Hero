<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/listaSolicitudes.css" rel="stylesheet">
    <link href="../styles/alert.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="cabecera">
        <div class="logo"><a href='<?php echo FRONT_ROOT . "Home/LogOut" ?>'><img src="../assets/img/PetHeroLogo.png" alt="Logo PetHero" height="100"></a>
        </div>
        <div><a href="<?php echo FRONT_ROOT . "Home/LogOut" ?>">LOG OUT</a></div>
    </div>
    <div class="contenedora-general">
        <div class="contenedora-section">

            <div class="listasolicitudes">
                <div class="titulo">
                    <h2>Solicitudes Pendientes</h2>
                </div>
                <div class="rotulo">
                    <div class="campo Nombre">Usuario</div>
                    <div class="campo Mascota">Mascota</div>
                    <div class="campo fecha">Fecha inicio</div>
                    <div class="campo fecha">Fecha fin</div>
                    <div class="campo costo">Costo</div>
                    <div class="campo aceptar">Aceptar</div>
                    <div class="campo rechazar">Rechazar</div>
                </div>
                <div class="scrolleable">
                    <?php foreach ($listaSolicitudes as $solicitud) { ?>
                        <div class="row solicitud">
                            <div class="col campo Nombre"><?php echo $solicitud->getDueño() ?></div>
                            <div class="col campo Mascota"><?php echo $solicitud->getMascota() ?></div>
                            <div class="col campo fecha"><?php echo $solicitud->getFechaInicio() ?></div>
                            <div class="col campo fecha"><?php echo $solicitud->getFechaFin() ?></div>
                            <div class="col campo costo"><?php echo "$".$solicitud->getCosto() ?></div>

                            <div class="col campo aceptar"><a href="../Reservas/AceptarSolicitud?idReserva=<?php echo $solicitud->getId() ?>"><img src="../assets/img/ok.png" alt="Aceptar"></a></div>

                            <div class="col campo rechazar"><a href="../Reservas/RechazarSolicitud?idReserva=<?php echo $solicitud->getId() ?>"><img src="../assets/img/rechaza.png" alt="Rechazar"></a></div>
                        </div>
                    <?php } ?>
                </div>

                <?php if (isset($alert)) { ?>
                    <div class="alert-<?php echo $alert->getType() ?>"><?php echo $alert->getMessage() ?></div>
                <?php } ?>
                
            </div>

        </div>

        <aside>
            <?php require_once(VIEWS_PATH . "dashboardGuardian/MenuDash.php"); ?>
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