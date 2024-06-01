<?php
session_start();
if (empty($_SESSION["id"])){
    header("location: ../sesiones/login.php");
}
if ($_SESSION["rol_id"] = 2){
    header("location: ../sesiones/inicio.php");
}
?>

Bienvenido alumno

<a href="../../../app/Controllers/sessiondestroy_controller.php"><center><input type="submit" name="btningresar" class="btn btn-success" value="Crerrar sesion"></center></a>