<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "encuesta_02";

// $host = '162.240.99.108';
// $db = 'desarrollosutvt_encuesta';
// $user = 'desarrollosutvt_mike';
// $pass = 'AIOM020605';
// $charset = 'utf8mb4';

$conexion = null;

try {
    $conexion = mysqli_connect($host, $user, $pass, $db);
} catch (mysqli_sql_exception $e) {
    // No hagas un die o exit aquí, simplemente deja $conexion como null
}
?>
