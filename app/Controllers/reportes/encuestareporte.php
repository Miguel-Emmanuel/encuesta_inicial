<?php
session_start();
require_once('../../../database/conexion.php');  // Conexión a la base de datos

// Obtener el ID del usuario y el rol desde la sesión
$usuario_id = $_SESSION['usuario_id'];

// Verificar si se ha enviado el estudiante
if (isset($_POST['estudiante_id'])) {
    $estudiante_id = $_POST['estudiante_id'];

    // Aquí puedes agregar el código que necesites para procesar el reporte, como consultas a la base de datos

    // Ejemplo de respuesta vacía (puedes modificarlo según lo que necesites)
    echo json_encode(["estudiante_id" => $estudiante_id]);
} else {
    echo json_encode(["error" => "No se ha enviado el estudiante_id"]);
}
?>



?>