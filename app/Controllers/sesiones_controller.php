<?php

if (!empty($_POST["btningresar"])){  /*SI EL BOTON HA SIDO PULSADO*/
    if (empty($_POST["email"]) and empty($_POST["password"])) {
        echo "POR FAVOR INGRESE SUS CREDENCIALES DE ACCESO";
    } else {
        # code...
    }
    
}

?>