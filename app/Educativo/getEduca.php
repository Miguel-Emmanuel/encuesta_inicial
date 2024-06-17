<?php
require '../Models/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);

$sql = "SELECT id, grado, nombre, clave From programa_edu WHERE id=$id LIMIT 1";
$resultado = $conexion->query($sql);
$rows = $resultado->num_rows;

$educa = [];

if($rows > 0){
    $educa = $resultado->fetch_array();
}

echo json_encode($educa, JSON_UNESCAPED_UNICODE);

?>