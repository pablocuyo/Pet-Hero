<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Favoritos</title>

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="../styles/dashboardDueño.css" rel="stylesheet">
    <link href="../styles/reviews.css" rel="stylesheet">
    <link href="../styles/alert.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="cabecera">
        <div class="logo"><a href='<?php echo FRONT_ROOT . "Home/LogOut" ?>'><img src="../assets/img/PetHeroLogo.png" height="100"></a>
        </div>
        <div><a href="<?php echo FRONT_ROOT . "Home/LogOut" ?>">LOG OUT</a></div>
    </div>
    <div class="contenedora-general">
        <div class="contenedora-section">
            <div class="conteiner-list">
                <div class="row encabezado-row">
                    <div class="col">Dueño</div>
                    <div class="col">Fecha</div>
                    <div class="col">Comentario</div>
                    <div class="col">Calificación</div>

                </div>
                <div class="scrolleable">
                    <?php foreach ($listaReviews as $review) { ?>

                        <div class="row review-row">

                            <div class="col nombre"><?php echo $review->getDueño() ?></div>
                            <div class="col fecha"><?php echo $review->getFecha() ?></div>
                            <div class="col comentario"><?php echo $review->getComentario() ?></div>
                            <div class="col"><a href=""><img src="../assets/img/<?php echo $review->getCalificacion() ?>_stars.png"></a></div>
                        </div>

                    <?php } ?>


                </div>
            </div>

            <?php if (isset($alert)) { ?>
                <div class="alert-<?php echo $alert->getType() ?>"><?php echo $alert->getMessage() ?></div>
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