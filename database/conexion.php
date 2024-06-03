<?php
// db.php
$host = 'localhost';
$db = 'ei';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $conexion = new mysqli($host, $user, $pass, $db);
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
} catch (\Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
