<?php
require '../Models/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$clave = $conexion->real_escape_string($_POST['clave']);
$grado = $conexion->real_escape_string($_POST['grado']);


$sql = "UPDATE programa_edu SET nombre = '$nombre', grado = '$grado', clave = '$clave' WHERE id = $id";

if($conexion->query($sql)){
   
}

header('Location: /public/views/educativo/index.php');

?>