<?php 
$host="localhost";
$user="root";
$pass="";
$dbname="encuesta_01";

$conexion= new mysqli($host, $user, $pass, $dbname);
$conexion->set_charset("utf8");

$verificacion = $conexion->query("DELETE FROM links WHERE created_at < (NOW() - INTERVAL 15 MINUTE);");

?>