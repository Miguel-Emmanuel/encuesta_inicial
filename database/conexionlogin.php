<?php
// Ajusta los datos de conexión a MySQL
$servername = "localhost";
$username = "root"; // Cambia si es necesario
$password = ""; // Cambia si es necesario
$dbname = "encuesta_02"; // Nombre de la base de datos

// Crea la conexión
$conexion = mysqli_connect($servername, $username, $password);

// Verifica la conexión
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Verifica si la base de datos existe antes de intentar seleccionarla
$db_check = mysqli_query($conexion, "SHOW DATABASES LIKE '$dbname'");
if (mysqli_num_rows($db_check) == 0) {
    // La base de datos no existe, asignamos $conexion a null para evitar intentar accederla más tarde
    $conexion = null;
} else {
    // Si la base de datos existe, la seleccionamos
    mysqli_select_db($conexion, $dbname);
}
?>
