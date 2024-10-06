<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, nombre, programa_e, nomenclatura, tutor, periodo_e From t_grupos WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$grupos = [];

if($rows > 0){
    $grupos = $resultado->fetch_array();
}

echo json_encode($grupos, JSON_UNESCAPED_UNICODE);

?>