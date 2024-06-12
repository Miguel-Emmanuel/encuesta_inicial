<?php
require '../Models/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);

$sql = "UPDATE roles SET nombre = '$nombre', descripcion = '$descripcion' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/roles/index.php');

?>