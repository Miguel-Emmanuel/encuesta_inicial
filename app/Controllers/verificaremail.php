<?php
include("../Models/conexion.php");

    $password1=$_POST["pass1"];
    $password2=$_POST["pass2"];
    $id=$_POST["id"];

        $PasswordHash = password_hash($password1, PASSWORD_BCRYPT); //Incriptando clave,
	    //crea un nuevo hash de contraseña usando un algoritmo de hash fuerte de único sentido.

        $sql="UPDATE usuarios SET pass = '$PasswordHash' WHERE id = '$id' ";
        $conexion->query($sql);
        $conexion->query("UPDATE usuarios SET email_verified = 1, email_verified_at = NOW() WHERE id = '$id'");
        $conexion->query("DELETE FROM links WHERE id_usuario = '$id'");
        header("Location: /app/Controllers/sessiondestroy_controller.php");
?>