<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombregv = $conexion->real_escape_string($_POST['nombregv']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);


$sql = "UPDATE gruposv SET nombregv = '$nombregv', descripcion = '$descripcion' WHERE id = $id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/gruposV/index.php');

?>