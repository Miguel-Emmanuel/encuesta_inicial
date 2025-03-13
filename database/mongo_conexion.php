<?php
// Incluir el autoload de Composer
require_once __DIR__ . '/../vendor/autoload.php';  // Ajusta la ruta si es necesario

use MongoDB\Client;

// Configuración de conexión a MongoDB
try {
    $client = new Client('mongodb://localhost:27017');  // Ajusta si es necesario
    $db = $client->encuesta;  // Nombre de la base de datos "encuesta"
} catch (Exception $e) {
    die("No se pudo conectar a MongoDB: " . $e->getMessage());
}
?>
