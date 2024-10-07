<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$alias = $conexion->real_escape_string($_POST['alias']);
$inicio = $conexion->real_escape_string($_POST['inicio']);
$fin = $conexion->real_escape_string($_POST['fin']);

$sql = "UPDATE periodos_escolar SET alias = '$alias', inicio = '$inicio', fin = '$fin' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/periodos/index.php');

?>