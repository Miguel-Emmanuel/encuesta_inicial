<?php
require '../../../database/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);

$sql = "INSERT INTO roles (nombre, descripcion) VALUES ('$nombre','$descripcion')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/roles/index.php');

?>