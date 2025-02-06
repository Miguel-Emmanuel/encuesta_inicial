<?php
session_start();
require_once('../../../database/conexion.php');  // Conexión a la base de datos

// Verificar si se ha enviado el estudiante
if (isset($_POST['estudiante'])) {
    $estudiante_id = $_POST['estudiante'];

    // Aquí puedes agregar el código que necesites para procesar el reporte, como consultas a la base de datos

    // Ejemplo de respuesta vacía (puedes modificarlo según lo que necesites)
    echo json_encode(["estudiante" => $estudiante_id]);
} else {
    echo json_encode(["error" => "No se ha enviado el estudiante_id"]);
}
?>