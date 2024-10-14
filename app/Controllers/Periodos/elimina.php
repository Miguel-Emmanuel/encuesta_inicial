<?php
require '../../../database/conexion.php';

$id = $conexion->real_escape_string($_POST['id']);


$sql = "UPDATE periodos_escolar SET activo = 0 WHERE id = $id";
if($conexion->query($sql)){
   
}

header('Location: /public/views/periodos/index.php');

?>