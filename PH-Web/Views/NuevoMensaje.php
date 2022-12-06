<div class="contendora">
   
        <div>
            <h2>Enviar mensaje a</h2> 
            <h1><?php echo $nombre ?></h1>
        </div>
        <form class="contenedora-reply" action="../Mensaje/Add" method="post">
            <input type="number" name="id" class="destinatario" value="<?php echo $id ?>" readonly>
            <textarea name="chat" class="reply" maxlength="150" placeholder="Max. 150 caracteres" size="50" required></textarea> 
            <div class="boton">
                 <button type="submit" class="submit"><img src="../assets/img/dogboneEnviar.png"></button>
             </div>
        </form>

</div>