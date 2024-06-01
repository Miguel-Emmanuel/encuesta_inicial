<?php
session_start();

if (!empty($_POST["btningresar"])){  /*SI EL BOTON HA SIDO PULSADO*/
    if (empty($_POST["email"]) and empty($_POST["password"])) {
        echo '<div class="alert alert-danger">"POR FAVOR INGRESE SUS CREDENCIALES DE ACCESO"</div>';
    } else {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = $conexion->query("SELECT * FROM usuarios WHERE email = '$email' AND pass = '$password'");
        if ($datos = $sql -> fetch_object()) {
            $_SESSION["id"] = $datos->id;
            $_SESSION["email"] = $datos->email;
            $_SESSION["nombres"] = $datos->nombres;
            $_SESSION["ap"] = $datos->apellido_paterno;
            $_SESSION["am"] = $datos->apellido_materno;
            $_SESSION["rol"] = $datos->rol_id;

            if ($_SESSION["rol"] = 1) {
                header("location: ../encuesta/encuesta.php");
            } else if ($_SESSION["rol"] = 2){
                header("location: inicio.php");
            }
            
        } else {
            echo '<div class="alert alert-danger">"ACCESO DENEGADO"</div>';
        }
        
    }
    
}

?>