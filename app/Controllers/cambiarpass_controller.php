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
        $sql="UPDATE usuarios SET pass = '$password1' WHERE id = '$id' ";
        $conexion->query($sql);
        echo '<script>alert("La contraseña ha sido actualizada, intente iniciar sesion."); window.location.href = "../sesiones/login.php";</script>';
    }
    }
?>