<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT e.id, e.estudiante_id, e.grupo_id, e.periodo_id FROM estudiante_grupo AS e
        WHERE e.id = $id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$estudiante_grupo = [];

if($rows > 0){
    $estudiante_grupo = $resultado->fetch_array();
}

echo json_encode($estudiante_grupo, JSON_UNESCAPED_UNICODE);

?>