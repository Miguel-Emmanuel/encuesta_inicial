<?php 
$host="localhost";
$user="root";
$pass="";
$dbname="encuesta";


// $host="162.240.99.108";
// $user="desarrollosutvt_mike";
// $pass="AIOM020605";
// $dbname="desarrollosutvt_encuesta";


$conexion= new mysqli($host, $user, $pass, $dbname);

$verificacion = $conexion->query("DELETE FROM links WHERE created_at < (NOW() - INTERVAL 15 MINUTE);");

?>