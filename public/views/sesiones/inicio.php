<?php
include("../../../app/Models/conexion.php");
session_start();
if (empty($_SESSION["id"])){
    header("location: login.php");
}

?>

HOLA PTC/Director
<a href="../../../app/Controllers/sessiondestroy_controller.php"><center><input type="submit" name="btningresar" class="btn btn-success" value="Crerrar sesion"></center></a>