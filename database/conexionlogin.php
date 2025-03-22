<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "encuesta_02";

$conexion = null;

try {
    $conexion = mysqli_connect($host, $user, $password, $database);
} catch (mysqli_sql_exception $e) {
    // No hagas un die o exit aquí, simplemente deja $conexion como null
}
?>
