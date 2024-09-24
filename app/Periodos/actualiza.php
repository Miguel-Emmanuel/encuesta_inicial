<?php
require '../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);
$inicio = $conexion->real_escape_string($_POST['inicio']);
$fin = $conexion->real_escape_string($_POST['fin']);

$sql = "UPDATE periodos_escolar SET inicio = '$inicio', fin = '$fin' WHERE id=$id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/periodos/index.php');

?>