<?php
include("../../../app/Models/conexion.php");

if (!empty($_POST["btncambiarpass"])) {
    $password1=$_POST["pass1"];
    $password2=$_POST["pass2"];
    $id=$_POST["id"];
    if(empty($_POST["pass1"]) or empty($_POST["pass2"])){
        echo '<div class="alert alert-danger">"Por favor vuelva a escribir su contraseña."</div>';
    }else if ($password1 !== $password2){
        echo '<div class="alert alert-danger">"Las contraseñas no coinciden."</div>';
    }else{

        $PasswordHash = password_hash($password1, PASSWORD_BCRYPT); //Incriptando clave,
	    //crea un nuevo hash de contraseña usando un algoritmo de hash fuerte de único sentido.

        $sql="UPDATE usuarios SET pass = '$PasswordHash' WHERE id = '$id' ";
        $conexion->query($sql);
        $conexion->query("DELETE FROM links WHERE id_usuario = '$id'");
        echo '<script>alert("La contraseña ha sido actualizada, intente iniciar sesion."); window.location.href = "../sesiones/login.php";</script>';
    }
    }
?>