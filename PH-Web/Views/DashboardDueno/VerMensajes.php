<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Perfil</title>
    
    <link href="../styles/dashboardDueño.css" rel="stylesheet" >
    <link href="../styles/verMensajes.css" rel="stylesheet" >
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
            <div class="contenedora-msjs aesthetic">
                <div>
                    <h2 class="title">Mensajes de Guardianes</h2>
                </div>
                <div class="bandeja-entrada aesthetic">
                <?php foreach($bandeja as $inbox){?>
                
                    <div class="row inbox aesthetic">
                        <div class="col user"><?php echo $inbox->getNombre();?></div>
                        <div class="col abrir"><a href="../Mensaje/VistaChat?id=<?php echo $inbox->getId();?>"><img src="../assets/img/open.png"></a></div>
                    </div>
                    <?php }
                    
                        ?> 
                </div> 
                
            </div>
        
            </div>
                <aside>
                    <?php require_once(VIEWS_PATH. "dashboardDueno/MenuDash.php");?>
                </aside>
    </div>
        

    <div class="footer-separador"></div>
    <footer>
        <div>Copyright &#169 2022 Pet Hero es un producto de Batti's System CO & Cuyo SA.</div>
        <div><a href="">Terminos y Condiciones</a></div>
        <div><a href="">Aviso de privacidad</a></div>
    </footer>

  </body>
  </html>