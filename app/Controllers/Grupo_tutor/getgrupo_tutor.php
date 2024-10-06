<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, grupo_id, tutor_id ,periodo_id FROM grupo_tutor 
        WHERE id = $id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$grupo_tutor = [];

if($rows > 0){
    $grupo_tutor = $resultado->fetch_array();
}

echo json_encode($grupo_tutor, JSON_UNESCAPED_UNICODE);

?>