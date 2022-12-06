<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Disponibilidad</title>

    <link href="../styles/regDisponibilidad.css" rel="stylesheet">

</head>

<body>
    <div class="cabecera">
        <div class="logo"><a href='../index.php'><img src="../assets/img/PetHeroLogo.png" height="100"></a>
        </div>
        <div><a href="<?php echo FRONT_ROOT . "Home/LogOut" ?>">LOG OUT</a></div>
    </div>
    <div class="contenedora">

        <form action="<?php echo FRONT_ROOT ?> Guardianes/Add" method="post" enctype="multipart/form-data">
            <div class="ultimos">
                <label for="disponibilidad">
                    <h4>Disponibilidad</h4>
                </label><br>
                <div class="ultimos">
                    <div>Desde<input type="date" name="inicio" class="" min="<?php echo date("Y-m-d"); ?>"></div>
                    <div>Hasta<input type="date" name="fin" class="" min="<?php echo date("Y-m-d"); ?>"></div>
                </div>

                <div>Tamaño de mascotas aceptado</div>
                <div class="tipo">
                    <div><input type="checkbox" name="sizes[]" value="Pequeño" class="" checked>Pequeños.</div>
                    <div><input type="checkbox" name="sizes[]" value="Mediano" class="">Medianos.</div>
                    <div><input type="checkbox" name="sizes[]" value="Grande" class="">Grandes.</div>
                </div>
                <div class="ultimos">
                    <div><label for="costo">Precio por dia</label></div>
                    <div class="datoregistro">
                        <input type="number" name="costo" class="" min="0"><br>
                    </div>

                    <div><label for="fotoEspacio">Foto del espacio</label></div>
                    <div class="datoregistro">
                        <input type="file" name="fotoEspacio" class="" required><br>
                    </div>
                    <div><label for="descripcion">Descripcion del espacio ofrecido</label></div>
                    <div class="datoregistro">
                        <input type="text" name="descripcion" class="descripcion" required><br>
                    </div>
                    <div class="boton">
                        <button type="submit" class="submit"><a href=""><img src="../assets/img/dogboneEnviar.png"></a></button>
                    </div>
                </div>
            </div>
</div>      
            <div class="separador"></div>
            <footer>
                <div>Copyright &#169 2022 Pet Hero S.A. es una empresa del grupo Batti's System CO.</div>
                <div><a href="">Terminos y Condiciones</a></div>
                <div><a href="">Aviso de privacidad</a></div>
            </footer>
</body>

</html>