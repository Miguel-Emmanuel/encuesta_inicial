<?php
// Datos de conexión a la base de datos MySQL
$host = 'localhost';  // Servidor MySQL
$user = 'root';       // Usuario MySQL
$pass = '';           // Contraseña de MySQL (vacía en tu caso)
$db   = 'encuesta_02';  // Nombre de la base de datos a usar (si ya tienes un nombre específico)

// Crear conexión
$connection = new mysqli($host, $user, $pass);

// Verificar conexión
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
}

// Si la conexión es exitosa, puedes usarla para interactuar con la base de datos
?>
