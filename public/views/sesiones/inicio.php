<?php
session_start();
if (empty($_SESSION["id"])){
    header("location: login.php");
}
if ($_SESSION["rol_id"] = 1){
    header("location: ../encuesta/encuesta.php");
}
?>

HOLA PTC
<a href="../../../app/Controllers/sessiondestroy_controller.php"><center><input type="submit" name="btningresar" class="btn btn-success" value="Crerrar sesion"></center></a>