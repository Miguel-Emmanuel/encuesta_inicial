<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$grado = $conexion->real_escape_string($_POST['grado']);
$nombre = $conexion->real_escape_string($_POST['nombre']);
$clave = $conexion->real_escape_string($_POST['clave']);



$sql = "UPDATE programa_edu SET grado = '$grado', nombre = '$nombre', clave = '$clave' WHERE id = $id";

if($conexion->query($sql)){
   
}

header('Location: /public/views/educativo/index.php');

?>