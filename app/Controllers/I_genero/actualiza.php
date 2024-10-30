<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$nombreig = $conexion->real_escape_string($_POST['nombreig']);
$descripcion = $conexion->real_escape_string($_POST['descripcion']);


$sql = "UPDATE i_genero SET nombreig = '$nombreig', descripcion = '$descripcion' WHERE id = $id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/i_genero/index.php');

?>