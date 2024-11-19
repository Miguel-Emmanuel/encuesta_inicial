<?php
require("../../../database/conexion.php");
// Obtener el ID de la pregunta desde el POST
$id_pregunta = $conexion->real_escape_string($_POST['id_pregunta']);

// Consulta para obtener las respuestas relacionadas
$sql = "SELECT estudiante_id, respuesta, created_at FROM respuestas WHERE pregunta_id = '$id_pregunta'";
$resultado = $conexion->query($sql);

// Preparar los datos para el JSON
$respuestas = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $respuestas[] = $fila;
    }
}

// Retornar los resultados en formato JSON
echo json_encode($respuestas, JSON_UNESCAPED_UNICODE);
?>
