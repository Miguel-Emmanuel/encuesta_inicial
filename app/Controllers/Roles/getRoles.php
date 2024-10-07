<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, nombre, descripcion From roles WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$roles = [];

if($rows > 0){
    $roles = $resultado->fetch_array();
}

echo json_encode($roles, JSON_UNESCAPED_UNICODE);

?>