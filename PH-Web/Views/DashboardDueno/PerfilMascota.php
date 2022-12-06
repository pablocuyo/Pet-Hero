<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/perfilMascota.css" rel="stylesheet">

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
            <div class="plantilla-Mascota">
                <div class="cont1 foto"><figure><img class="pic" src="../assets/Mascotas/FotosMascotas/<?php echo $mascota->getFotoUrl();?>"></figure></div>
                <div class="cont2 datos">
                <div class="atributo">Nombre:</div><div class="text nombre"><?php echo $mascota->getNombre();?></div>
                <div class="atributo">Raza:</div><div class="text raza"><?php echo $mascota->getRaza();?></div>
                <div class="atributo">Especie:</div><div class="text especie"><?php echo $mascota->getEspecie();?></div>
                <div class="atributo">Tamaño:</div><div class="text tamaño"><?php echo $mascota->getTamaño();?></div>
                </div>
                
                <div class="cont3">
                    <div class="vacunacion">Libreta de Vacunacion</div>
                    <div><figure><img class="libreta" src="../assets/Mascotas/PlanesVacunacion/<?php echo $mascota->getPlanVacURL();?>"></figure></div>
                </div>
                <div class="cont4 video">Pincha acá para ver el video de<a href="<?php echo $mascota->getVideoURL();?>"> <?php echo $mascota->getNombre();?></a></div>
            </div>
                
        </div>
        <aside>
        <?php require_once(VIEWS_PATH. "dashboardDueno/MenuDash.php");?>
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