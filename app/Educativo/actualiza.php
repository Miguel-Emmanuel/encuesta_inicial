<?php
require '../Models/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombre = $conexion->real_escape_string($_POST['nombre']);


$sql = "UPDATE programa_edu SET nombre = '$nombre' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/educativo/index.php');

?>