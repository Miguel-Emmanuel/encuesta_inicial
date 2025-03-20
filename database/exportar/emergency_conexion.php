<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "encuesta_02";

$conexion = null;
$conexion_exitosa = false; // Variable para indicar si la conexión fue exitosa

try {
    $conexion = mysqli_connect($host, $user, $pass);
    if ($conexion) {
        $conexion_exitosa = true; // La conexión fue exitosa
    }
} catch (mysqli_sql_exception $e) {
    // No hagas un die o exit aquí, simplemente deja $conexion como null
    $conexion_exitosa = false; // La conexión falló
}
?>