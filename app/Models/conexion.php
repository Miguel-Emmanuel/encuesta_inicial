<?php 
$host="localhost";
$user="root";
$pass="";
$dbname="EI";

$conexion= new mysqli($host, $user, $pass, $dbname);
$conexion->set_charset("utf8");
?>