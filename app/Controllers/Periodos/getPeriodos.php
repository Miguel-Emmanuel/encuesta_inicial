<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, alias, inicio, fin From periodos_escolar WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$periodos = [];

if($rows > 0){
    $periodos = $resultado->fetch_array();
}

echo json_encode($periodos, JSON_UNESCAPED_UNICODE);

?>