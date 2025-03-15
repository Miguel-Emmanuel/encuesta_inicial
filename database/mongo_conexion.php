<?php
// Construir la ruta a autoload.php de manera segura
$autoloadPath = __DIR__ . '../../vendor/autoload.php';

require $autoloadPath; // Cargar las dependencias de Composer

use MongoDB\Client;

// Crear conexión con MongoDB
$mongoClient = new Client("mongodb://localhost:27017"); // Ajusta la conexión si es necesario
$mongoDB = $mongoClient->encuesta; // Base de datos
$collection = $mongoDB->respaldos; // Colección
?>
