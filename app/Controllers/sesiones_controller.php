<?php

if (!empty($_POST["btningresar"])){  /*SI EL BOTON HA SIDO PULSADO*/
    if (empty($_POST["email"]) and empty($_POST["password"])) {
        echo '<div class="alert alert-danger">"POR FAVOR INGRESE SUS CREDENCIALES DE ACCESO"</div>';
    } else {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = $conexion->query("SELECT * FROM users WHERE email = $email AND pass = $password");
        if ($datos = $sql -> fetch_object()) {
            header("location: ../../public/views/sesiones/ ");
        } else {
            # code...
        }
        
    }
    
}

?>