<?php
// Intentar conexión a MariaDB
$servername = "localhost";
$username = "root"; // Cambia esto si usas un usuario diferente
$password = ""; // Cambia esto si usas una contraseña

// Intentamos conectar a la base de datos MySQL
$conexion = mysqli_connect($servername, $username, $password);  // Cambié $conexion_mariadb a $conexion

// Verificamos si la conexión fue exitosa
if (!$conexion) {
    // Si no se puede conectar a MySQL, pasamos a la conexión de MongoDB
    // No debemos mostrar error de conexión aquí, solo conectamos con MongoDB
    include_once('mongo_conexion.php');
    
    // Si la conexión con MongoDB fue exitosa, $db estará disponible
    if (isset($db)) {
        echo "Conectado a MongoDB.";
    } else {
        die("No se pudo conectar a MongoDB.");
    }
} else {
    // Si la conexión a MariaDB fue exitosa, seleccionamos la base de datos
    $db_name = "encuesta_02";
    $db_selected = mysqli_select_db($conexion, $db_name); // Usé $conexion en vez de $conexion_mariadb

    if (!$db_selected) {
        // Si no existe la base de datos, no mostramos error
        echo "Base de datos no encontrada, pasando a MongoDB.";
        // Conectamos a MongoDB si no encontramos la base de datos en MySQL
        include_once('mongo_conexion.php');
    } else {
        echo "Conectado a MySQL.";
        // Aquí puedes continuar con las operaciones en MariaDB si la base de datos existe
    }
}
?>
