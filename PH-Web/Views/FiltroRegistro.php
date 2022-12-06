<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro</title>

  <link href="../styles/filtroRegistro.css" rel="stylesheet">
  <link href="../styles/alert.css" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@600&display=swap" rel="stylesheet">

</head>

<body>

  <div class="cabecera">
    <!--contenedora para el logo en el top + faqs, etc -->
    <a href='../index.php'><img src="../assets/img/PetHeroLogo.png" height="120"></a>
  </div>
  <div class="contenedora-gral">


    <!--aca esta el contenedor q contiene las 2 opciones -->

    <div class="contenedora-eleccion dueño">
      <!--contenedora para eleccion de dueño -->

      <div class="contenedora-titulo">
        <img src="../assets/img/icono-Dueño.png" height="400">
      </div>
      <div class="superheroicon"><img src="../assets/img/petsuperhero-removebg-preview.png"> </div>
      <div class="contenedora-texto">
        <div class="texto">
          <p>
            Deberás brindar datos personales y de contacto.<br>
            Podrás registrar las mascotas que desees.<br>
            Una vez hecho ya podras usar los servicios de la web. <br>
          </p>
        </div>
      </div>

      <div class="contenedora-boton">
        <a href="<?php echo FRONT_ROOT . "Duenos/VistaRegistro" ?>"><img src="../assets/img/RegistrarmeResized.png" height="50"></a>
      </div>

    </div>

    <div class="contenedora-eleccion guardian">
      <!--acontenedora para eleccion de guardian -->

      <div class="contenedora-titulo">
        <img src="../assets/img/My Project-1.png">
      </div>
      <div class="superheroicon"><img src="../assets/img/petsuperhero-removebg-preview.png"> </div>

      <div class="contenedora-texto">
        <div class="texto">Deberas brindar datos personales y de contacto.<br> Será necesario agregar información acerca <br>del espacio brindado para el cuidado de las mascotas como asi mísmo <br>una descripción del servicio ofrecído <br>y registrar la disponibilidad con la que lo harás.</div>
      </div>

      <div class="contenedora-boton">
        <a href="<?php echo FRONT_ROOT . "Guardianes/VistaRegistro" ?>"><img src="../assets/img/RegistrarmeResized.png" height="50"></a>
      </div>
    </div>

  </div>
  <div class="top-footer"></div>
  <!--ofrece un margen inferior por encima del footer -->


  <footer>
    <div>Copyright &#169 2022 Pet Hero S.A. es una empresa del grupo Batti's System CO.</div>
    <div><a href="">Terminos y Condiciones</a></div>
    <div><a href="">Aviso de privacidad</a></div>
  </footer>

</html>