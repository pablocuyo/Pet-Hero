<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar disponibilidad</title>

    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/editarPerfil.css" rel="stylesheet">
    <link href="../styles/alert.css" rel="stylesheet">
    <link href="../styles/editarDisponibilidad.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="cabecera">
        <div class="logo"><a href='<?php echo FRONT_ROOT . "Home/LogOut"?>'><img src="../assets/img/PetHeroLogo.png" alt="Logo PetHero" height="100"></a>
        </div>
        <div><a href="<?php echo FRONT_ROOT . "Home/LogOut" ?>">LOG OUT</a></div>
    </div>
    <div class="contenedora-general">
        <div class="contenedora-section">
            <div class="setDispo">
                <form action="<?php echo FRONT_ROOT ?> Guardianes/actualizarDisponibilidad" method="post">

                    <h3 class="title">Establecer disponiblidad</h3>
                    <div class="disponibilidad">
                        <div>Fecha inicio<input type="date" name="fechaInicio" min="<?php echo date("Y-m-d"); ?>"></div>
                        <div>Fecha Fin<input type="date" name="fechaFin" min="<?php echo date("Y-m-d"); ?>"></div>
                    </div>

                    <div><label for="tamano">Tamaño de mascotas aceptado</label></div>
                    <div class="tipo">
                        <div><input type="checkbox" name="sizes[]" value="Pequeño" class="" checked>Pequeños.</div>
                        <div><input type="checkbox" name="sizes[]" value="Mediano" class="">Medianos.</div>
                        <div><input type="checkbox" name="sizes[]" value="Grande" class="">Grandes.</div>
                    </div>
                    <div class="ultimos">
                        <div><label for="costo">Precio por dia</label></div>
                        <div class="datoregistro">
                            <input type="number" name="costo" class="" value="<?php echo $guardian->getCosto(); ?>">
                        </div>

                        <div><label for="fotoEspacio">Foto del espacio.<br>(Colocar direccion URL de la imagen)</label></div>
                        <div class="datoregistro">
                            <input type="text" name="fotoUrl" class="" value="<?php echo $guardian->getFotoEspacioURL(); ?>" required><br>
                        </div>
                        <div><label for="descripcion">Descripcion del espacio ofrecido</label></div>
                        <div class="datoregistro">
                            <input type="text" name="descripcion" class="descripcion" value="<?php echo $guardian->getDescripcion(); ?>" required><br>
                        </div>
                        <div class="boton">
                            <button type="submit" class="submit"><a href=""><img src="../assets/img/dogboneEnviar.png" alt="Enviar"></a></button>
                        </div>
                    </div>

                    <?php if (isset($alert)) { ?>
                     <div class="alert-<?php echo $alert->getType()?>"><?php echo $alert->getMessage()?></div>
                 <?php } ?>
                </form>

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