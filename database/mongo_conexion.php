    <?php
    // ESTE DOCUMENTO YA NO ES DE MONGODB, ES DE MYSQL, PERO ME DIO FLOJERA CAMBIAR LOS NOMBRES EN TODOS LOS ARCHIVOS
    /* $autoloadPath = __DIR__ . '../../vendor/autoload.php';

    require $autoloadPath; // Cargar las dependencias de Composer

    use MongoDB\Client;

    // Crear conexión con MongoDB
    $mongoClient = new Client("mongodatabase://localhost:27017"); // Ajusta la conexión si es necesario
    $mongoDB = $mongoClient->encuesta; // Base de datos
    $collection = $mongoDB->respaldos; // Colección */

    // $host = "localhost";
    // $user = "root";
    // $password = "";
  // $database = "respaldo";


    $host = '162.240.99.108';
$database = 'desarrollosutvt_respaldo_encuesta';
$user = 'desarrollosutvt_mike';
$password = 'AIOM020605';
$charset = 'utf8mb4';
    $conexion_respaldo = null;

    try {
        $conexion_respaldo = mysqli_connect($host, $user, $password, $database);
    } catch (mysqli_sql_exception $e) {
        // No hagas un die o exit aquí, simplemente deja $conexion como null
    }



    /* CREATE DATABASE respaldo;
USE respaldo;

CREATE TABLE `usuarios` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `apellido_paterno` VARCHAR(50) NOT NULL,
  `apellido_materno` VARCHAR(50) NOT NULL,
  `email` VARCHAR(250) NOT NULL,
  `email_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` TEXT NOT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `rol_id` BIGINT(20) UNSIGNED NOT NULL,
  `activo` INT(11) DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=INNODB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` VALUES 
(1, 'Director', 'Admin', 'General', 'director@gmail.com', 1, '2025-02-27 15:56:22', '$2y$10$aER9aGyQDx3kDNS8I8tUseDYXSRTMB6eiGZ6XwjJH768ur7Uczj2C', NULL, 1, 1, '2025-02-27 15:56:22', '2025-02-27 15:56:22');

CREATE TABLE `respaldos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL DEFAULT '',
  `ruta` TEXT NOT NULL DEFAULT '',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; */


    ?>


